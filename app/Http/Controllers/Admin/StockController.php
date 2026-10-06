<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Display stock matrix of all products with shoe sizes
     */
    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'category', 'sizeStocks']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('stock_status')) {
            if ($request->input('stock_status') === 'low') {
                $query->whereHas('sizeStocks', function ($s) {
                    $s->where('stock', '>', 0)->where('stock', '<=', 3);
                });
            } elseif ($request->input('stock_status') === 'out') {
                $query->whereDoesntHave('sizeStocks', function ($s) {
                    $s->where('stock', '>', 0);
                });
            }
        }

        $products = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $sizes = Size::orderBy('sort_order')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.stocks.index', compact('products', 'sizes', 'categories', 'brands'));
    }

    /**
     * Bulk update product stocks
     */
    public function bulkUpdate(Request $request): RedirectResponse|JsonResponse
    {
        $stocks = $request->input('stocks', []);

        foreach ($stocks as $productId => $sizeData) {
            if (is_array($sizeData)) {
                foreach ($sizeData as $sizeId => $qty) {
                    if (is_numeric($qty) && $qty >= 0) {
                        ProductSizeStock::updateOrCreate(
                            ['product_id' => (int) $productId, 'size_id' => (int) $sizeId],
                            ['stock' => (int) $qty]
                        );
                    }
                }
            }
        }

        if (!empty($stocks)) {
            \App\Models\AuditLog::record(
                'stocks_bulk_updated',
                Product::class,
                null,
                'Toplu stok güncellemesi yapıldı (' . count($stocks) . ' ürün etkilendi).'
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Stoklar başarıyla güncellendi.']);
        }

        return back()->with('success', 'Stok miktarları başarıyla güncellendi.');
    }

    /**
     * Quick single stock update via AJAX
     */
    public function quickUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'stock' => 'required|integer|min:0|max:9999',
        ]);

        $stockRecord = ProductSizeStock::updateOrCreate(
            ['product_id' => $validated['product_id'], 'size_id' => $validated['size_id']],
            ['stock' => $validated['stock']]
        );

        \App\Models\AuditLog::record(
            'stock_quick_updated',
            Product::class,
            $validated['product_id'],
            "Ürün #{$validated['product_id']} Beden #{$validated['size_id']} stok miktarı {$validated['stock']} yapıldı."
        );

        return response()->json([
            'success' => true,
            'message' => 'Stok güncellendi',
            'stock' => $stockRecord->stock,
        ]);
    }
}
