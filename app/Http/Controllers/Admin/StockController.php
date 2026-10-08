<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Size;
use App\Models\StockMovement;
use App\Models\StockNotification;
use App\Services\StockNotificationService;
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

        // Pending stock notifications count
        $pendingStockAlertsCount = 0;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_notifications')) {
                $pendingStockAlertsCount = StockNotification::where('is_notified', false)->count();
            }
        } catch (\Throwable $e) {
            $pendingStockAlertsCount = 0;
        }

        return view('admin.stocks.index', compact('products', 'sizes', 'categories', 'brands', 'pendingStockAlertsCount'));
    }

    /**
     * Bulk update product stocks
     */
    public function bulkUpdate(Request $request): RedirectResponse|JsonResponse
    {
        $stocks = $request->input('stocks', []);
        $updatedCount = 0;

        foreach ($stocks as $productId => $sizeData) {
            if (is_array($sizeData)) {
                foreach ($sizeData as $sizeId => $qty) {
                    if (is_numeric($qty) && $qty >= 0) {
                        $newStock = (int) $qty;
                        $existing = ProductSizeStock::where('product_id', (int) $productId)
                                                    ->where('size_id', (int) $sizeId)
                                                    ->first();
                        $qtyBefore = $existing ? $existing->stock : 0;

                        if ($qtyBefore !== $newStock) {
                            ProductSizeStock::updateOrCreate(
                                ['product_id' => (int) $productId, 'size_id' => (int) $sizeId],
                                ['stock' => $newStock]
                            );

                            StockMovement::create([
                                'product_id' => (int) $productId,
                                'size_id' => (int) $sizeId,
                                'user_id' => auth()->id(),
                                'type' => 'manual_update',
                                'quantity_before' => $qtyBefore,
                                'quantity_change' => $newStock - $qtyBefore,
                                'quantity_after' => $newStock,
                                'reason' => 'Toplu stok matrisi güncellemesi',
                                'reference_type' => 'Product',
                                'reference_id' => (int) $productId,
                            ]);

                            if ($qtyBefore === 0 && $newStock > 0) {
                                $productObj = Product::find((int) $productId);
                                $sizeObj = Size::find((int) $sizeId);
                                if ($productObj && $sizeObj) {
                                    StockNotificationService::notifySubscribers($productObj, $sizeObj, $newStock);
                                }
                            }

                            $updatedCount++;
                        }
                    }
                }
            }
        }

        if ($updatedCount > 0) {
            AuditLog::record(
                'stocks_bulk_updated',
                Product::class,
                null,
                "Toplu stok güncellemesi yapıldı ({$updatedCount} numara/ürün stoku güncellendi)."
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

        $existing = ProductSizeStock::where('product_id', $validated['product_id'])
                                    ->where('size_id', $validated['size_id'])
                                    ->first();
        $qtyBefore = $existing ? $existing->stock : 0;
        $newStock = $validated['stock'];

        $stockRecord = ProductSizeStock::updateOrCreate(
            ['product_id' => $validated['product_id'], 'size_id' => $validated['size_id']],
            ['stock' => $newStock]
        );

        if ($qtyBefore !== $newStock) {
            StockMovement::create([
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'user_id' => auth()->id(),
                'type' => 'manual_update',
                'quantity_before' => $qtyBefore,
                'quantity_change' => $newStock - $qtyBefore,
                'quantity_after' => $newStock,
                'reason' => 'Hızlı stok güncellemesi',
                'reference_type' => 'Product',
                'reference_id' => $validated['product_id'],
            ]);

            if ($qtyBefore === 0 && $newStock > 0) {
                $productObj = Product::find($validated['product_id']);
                $sizeObj = Size::find($validated['size_id']);
                if ($productObj && $sizeObj) {
                    StockNotificationService::notifySubscribers($productObj, $sizeObj, $newStock);
                }
            }
        }

        AuditLog::record(
            'stock_quick_updated',
            Product::class,
            $validated['product_id'],
            "Ürün #{$validated['product_id']} Beden #{$validated['size_id']} stok miktarı {$qtyBefore} -> {$newStock} yapıldı."
        );

        return response()->json([
            'success' => true,
            'message' => 'Stok güncellendi',
            'stock' => $stockRecord->stock,
        ]);
    }
}
