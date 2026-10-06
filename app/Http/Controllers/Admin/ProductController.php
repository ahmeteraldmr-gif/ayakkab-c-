<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSizeStock;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'images', 'sizeStocks.size']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('color', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show create product form
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories', 'brands', 'sizes'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'gender' => 'required|in:erkek,kadin,unisex,cocuk',
            'color' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:20',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Ürün adı zorunludur.',
            'price.required' => 'Ürün fiyatı zorunludur.',
            'discount_price.lt' => 'İndirimli fiyat normal fiyattan küçük olmalıdır.',
        ]);

        try {
            DB::beginTransaction();

            $slug = Str::slug($validated['name']);
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'sku' => !empty($validated['sku']) ? $validated['sku'] : 'YSA-' . strtoupper(Str::random(6)),
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'gender' => $validated['gender'],
                'color' => $validated['color'] ?? null,
                'color_code' => $validated['color_code'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'meta_title' => $validated['meta_title'] ?? $validated['name'],
                'meta_description' => $validated['meta_description'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'is_new' => $request->boolean('is_new', true),
            ]);

            // Save Images
            if ($request->hasFile('images')) {
                $isPrimary = true;
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $isPrimary,
                        'sort_order' => $index,
                    ]);
                    $isPrimary = false;
                }
            }

            // Save Size Stocks
            if ($request->filled('stocks')) {
                foreach ($request->input('stocks') as $sizeId => $stockQty) {
                    if (is_numeric($stockQty) && $stockQty >= 0) {
                        ProductSizeStock::create([
                            'product_id' => $product->id,
                            'size_id' => (int) $sizeId,
                            'stock' => (int) $stockQty,
                        ]);
                    }
                }
            }

            $this->normalizeProductImages($product->id);

            \App\Models\AuditLog::record(
                'product_created',
                Product::class,
                $product->id,
                "Yeni ürün eklendi: {$product->name} (Fiyat: {$product->price} TL)"
            );

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ürün kaydedilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'sizeStocks']);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();

        $currentStocks = $product->sizeStocks->pluck('stock', 'size_id')->toArray();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'sizes', 'currentStocks'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'gender' => 'required|in:erkek,kadin,unisex,cocuk',
            'color' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:20',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'gender' => $validated['gender'],
                'color' => $validated['color'] ?? null,
                'color_code' => $validated['color_code'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'meta_title' => $validated['meta_title'] ?? $validated['name'],
                'meta_description' => $validated['meta_description'] ?? null,
                'is_active' => $request->boolean('is_active', false),
                'is_featured' => $request->boolean('is_featured', false),
                'is_new' => $request->boolean('is_new', false),
            ]);

            // Save New Images
            if ($request->hasFile('images')) {
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => !$hasPrimary && $index === 0,
                        'sort_order' => $product->images()->count() + $index,
                    ]);
                }
            }

            // Update Size Stocks
            if ($request->filled('stocks')) {
                foreach ($request->input('stocks') as $sizeId => $stockQty) {
                    ProductSizeStock::updateOrCreate(
                        ['product_id' => $product->id, 'size_id' => (int) $sizeId],
                        ['stock' => (int) ($stockQty ?? 0)]
                    );
                }
            }

            $this->normalizeProductImages($product->id);

            \App\Models\AuditLog::record(
                'product_updated',
                Product::class,
                $product->id,
                "Ürün güncellendi: {$product->name}"
            );

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Güncelleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Delete product
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $productName = $product->name;
            $productId = $product->id;

            foreach ($product->images as $img) {
                if ($img->image_path && !str_starts_with($img->image_path, 'http')) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            $product->delete();

            \App\Models\AuditLog::record(
                'product_deleted',
                Product::class,
                $productId,
                "Ürün silindi: {$productName}"
            );

            return redirect()->route('admin.products.index')->with('success', 'Ürün ve ilişkili görseller başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ürün silinemedi: ' . $e->getMessage());
        }
    }

    /**
     * Delete single product image
     */
    public function deleteImage(ProductImage $image): JsonResponse|RedirectResponse
    {
        $productId = $image->product_id;
        if ($image->image_path && !str_starts_with($image->image_path, 'http')) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        $this->normalizeProductImages($productId);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Görsel silindi.');
    }

    /**
     * Set Primary Image
     */
    public function setPrimaryImage(ProductImage $image): RedirectResponse
    {
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        $this->normalizeProductImages($image->product_id);

        return back()->with('success', 'Kapak görseli belirlendi.');
    }

    /**
     * Normalize product images sort_order and guarantee exactly one primary image
     */
    protected function normalizeProductImages(int $productId): void
    {
        $images = ProductImage::where('product_id', $productId)->orderBy('sort_order')->orderBy('id')->get();
        
        $hasPrimary = false;
        foreach ($images as $index => $img) {
            $updateData = ['sort_order' => $index];
            if ($img->is_primary && !$hasPrimary) {
                $hasPrimary = true;
            } elseif ($img->is_primary && $hasPrimary) {
                $updateData['is_primary'] = false;
            }
            $img->update($updateData);
        }

        if (!$hasPrimary && $images->isNotEmpty()) {
            $images->first()->update(['is_primary' => true]);
        }
    }
}
