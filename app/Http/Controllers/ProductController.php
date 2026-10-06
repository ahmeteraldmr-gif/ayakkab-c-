<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display product catalog with advanced filtering
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Product::with(['brand', 'category', 'images', 'sizes', 'sizeStocks'])
                        ->where('is_active', true);

        // Filter: Search Keyword (Smart Search across product name, sku, description, brand name, category name)
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $words = explode(' ', $keyword);
            
            $query->where(function ($q) use ($words, $keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('color', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('brand', function ($b) use ($keyword) {
                      $b->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('category', function ($c) use ($keyword) {
                      $c->where('name', 'like', "%{$keyword}%");
                  });

                // Multi-word matching (e.g. "siyah spor")
                if (count($words) > 1) {
                    $q->orWhere(function ($sub) use ($words) {
                        foreach ($words as $w) {
                            if (mb_strlen($w) > 1) {
                                $sub->where(function ($wGroup) use ($w) {
                                    $wGroup->where('name', 'like', "%{$w}%")
                                           ->orWhere('color', 'like', "%{$w}%")
                                           ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$w}%"))
                                           ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$w}%"));
                                });
                            }
                        }
                    });
                }
            });
        }

        // Filter: Gender (Erkek, Kadin, Unisex, Cocuk)
        if ($request->filled('gender')) {
            $genders = (array) $request->input('gender');
            $query->whereIn('gender', $genders);
        }

        // Filter: Category
        if ($request->filled('category')) {
            $categories = (array) $request->input('category');
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories)->orWhereIn('id', $categories);
            });
        }

        // Filter: Brand
        if ($request->filled('brand')) {
            $brands = (array) $request->input('brand');
            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('slug', $brands)->orWhereIn('id', $brands);
            });
        }

        // Filter: Size Numbers
        if ($request->filled('size')) {
            $sizes = (array) $request->input('size');
            $query->whereHas('sizeStocks', function ($q) use ($sizes) {
                $q->where('stock', '>', 0)
                  ->whereHas('size', function ($sq) use ($sizes) {
                      $sq->whereIn('size_number', $sizes)->orWhereIn('id', $sizes);
                  });
            });
        }

        // Filter: Color
        if ($request->filled('color')) {
            $colors = (array) $request->input('color');
            $query->whereIn('color', $colors);
        }

        // Filter: Min & Max Price
        if ($request->filled('min_price')) {
            $minPrice = (float) $request->input('min_price');
            $query->where(function ($q) use ($minPrice) {
                $q->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('discount_price')
                        ->where('discount_price', '>=', $minPrice);
                })->orWhere(function ($sub) use ($minPrice) {
                    $sub->whereNull('discount_price')
                        ->where('price', '>=', $minPrice);
                });
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $query->where(function ($q) use ($maxPrice) {
                $q->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('discount_price')
                        ->where('discount_price', '<=', $maxPrice);
                })->orWhere(function ($sub) use ($maxPrice) {
                    $sub->whereNull('discount_price')
                        ->where('price', '<=', $maxPrice);
                });
            });
        }

        // Filter: Discounted Only
        if ($request->boolean('discounted')) {
            $query->whereNotNull('discount_price')
                  ->whereColumn('discount_price', '<', 'price');
        }

        // Filter: In Stock Only
        if ($request->boolean('in_stock')) {
            $query->whereHas('sizeStocks', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            'popular' => $query->orderByDesc('view_count'),
            'discount' => $query->whereNotNull('discount_price')->orderByRaw('((price - discount_price) / price) DESC'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(12)->withQueryString();

        // Data for Filter Sidebar
        $availableCategories = Category::where('is_active', true)->withCount('products')->get();
        $availableBrands = Brand::where('is_active', true)->withCount('products')->get();
        $availableSizes = Size::orderBy('sort_order')->get();
        $availableColors = Product::where('is_active', true)
                                  ->whereNotNull('color')
                                  ->distinct()
                                  ->pluck('color');

        // Price range bounds
        $priceMinBound = (float) Product::where('is_active', true)->min('price') ?? 0;
        $priceMaxBound = (float) Product::where('is_active', true)->max('price') ?? 5000;

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('partials.product-grid', compact('products'))->render();
            $paginationHtml = $products->links('pagination::tailwind')->render();
            return response()->json([
                'html' => $html,
                'pagination' => $paginationHtml,
                'total' => $products->total(),
                'count_text' => "<strong class=\"text-dark font-bold\">{$products->total()}</strong> ürün bulundu",
            ]);
        }

        return view('pages.products', compact(
            'products',
            'availableCategories',
            'availableBrands',
            'availableSizes',
            'availableColors',
            'priceMinBound',
            'priceMaxBound'
        ));
    }

    /**
     * Display product detail page
     */
    public function show(string $slug): View
    {
        $product = Product::with([
            'brand',
            'category',
            'images',
            'sizeStocks.size',
            'sizes'
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        // Available sizes with stocks
        $sizesWithStock = $product->sizeStocks->map(function ($item) {
            return [
                'size_id' => $item->size_id,
                'size_number' => $item->size?->size_number ?? '',
                'stock' => (int) $item->stock,
                'in_stock' => $item->stock > 0,
                'is_low_stock' => $item->stock > 0 && $item->stock <= 3,
            ];
        })->sortBy('size_number');

        // Related Products (Same category or brand, excluding current)
        $relatedProducts = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                                  ->where('is_active', true)
                                  ->where('id', '!=', $product->id)
                                  ->where(function ($q) use ($product) {
                                      if ($product->category_id) {
                                          $q->where('category_id', $product->category_id);
                                      }
                                      if ($product->brand_id) {
                                          $q->orWhere('brand_id', $product->brand_id);
                                      }
                                  })
                                  ->take(4)
                                  ->get();

        if ($relatedProducts->count() < 4) {
            $extra = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                            ->where('is_active', true)
                            ->where('id', '!=', $product->id)
                            ->whereNotIn('id', $relatedProducts->pluck('id'))
                            ->take(4 - $relatedProducts->count())
                            ->get();
            $relatedProducts = $relatedProducts->concat($extra);
        }

        // WhatsApp inquiry link generator
        $waPhone = Setting::get('site_whatsapp', '905550000000');
        $cleanPhone = preg_replace('/[^0-9]/', '', $waPhone);
        $productUrl = url()->current();
        $waMessage = rawurlencode("Merhaba Yusuf Akboğa Ayakkabı, {$product->name} (Kod: {$product->sku}) ürünü hakkında bilgi almak istiyorum. Ürün linki: {$productUrl}");
        $whatsappLink = "https://wa.me/{$cleanPhone}?text={$waMessage}";

        return view('pages.product-detail', compact(
            'product',
            'sizesWithStock',
            'relatedProducts',
            'whatsappLink'
        ));
    }

    /**
     * Fast Live Search API endpoint
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::with(['brand', 'images'])
                           ->where('is_active', true)
                           ->where(function ($q) use ($query) {
                               $q->where('name', 'like', "%{$query}%")
                                 ->orWhere('sku', 'like', "%{$query}%")
                                 ->orWhere('color', 'like', "%{$query}%")
                                 ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$query}%"))
                                 ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$query}%"));
                           })
                           ->take(6)
                           ->get();

        $results = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'brand' => $p->brand?->name ?? '',
                'price' => $p->formatted_effective_price,
                'old_price' => $p->has_discount ? $p->formatted_price : null,
                'image' => $p->primary_image_url,
                'url' => route('product.detail', $p->slug),
            ];
        });

        return response()->json(['results' => $results]);
    }
}
