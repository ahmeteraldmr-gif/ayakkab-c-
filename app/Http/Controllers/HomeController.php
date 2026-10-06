<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Active Hero Campaigns & Banners
        $heroCampaigns = Campaign::where('is_active', true)
                                 ->orderBy('sort_order')
                                 ->orderByDesc('id')
                                 ->get();

        // Main Categories with product count
        $categories = Category::where('is_active', true)
                              ->withCount(['products' => function ($q) {
                                  $q->where('is_active', true);
                              }])
                              ->orderBy('sort_order')
                              ->get();

        // New Arrivals (Son eklenen 8 ürün)
        $newArrivals = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                              ->where('is_active', true)
                              ->orderByDesc('is_new')
                              ->orderByDesc('created_at')
                              ->take(8)
                              ->get();

        // Real Best Sellers Calculation based on actual Order Items (excluding cancelled orders)
        $topSoldProductIds = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                                      ->where('orders.status', '!=', 'iptal')
                                      ->whereNotNull('order_items.product_id')
                                      ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
                                      ->groupBy('order_items.product_id')
                                      ->orderByDesc('total_sold')
                                      ->take(8)
                                      ->pluck('product_id')
                                      ->toArray();

        $bestSellers = collect();
        if (!empty($topSoldProductIds)) {
            $fetched = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                              ->where('is_active', true)
                              ->whereIn('id', $topSoldProductIds)
                              ->get()
                              ->sortBy(function ($model) use ($topSoldProductIds) {
                                  return array_search($model->id, $topSoldProductIds);
                              })
                              ->values();
            $bestSellers = $fetched;
        }

        // Fallback to featured / high view count if less than 4 actual best-sellers found
        if ($bestSellers->count() < 4) {
            $excludeIds = $bestSellers->pluck('id')->toArray();
            $fallback = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                               ->where('is_active', true)
                               ->whereNotIn('id', $excludeIds)
                               ->where(function ($q) {
                                   $q->where('is_featured', true)
                                     ->orWhere('view_count', '>', 5);
                               })
                               ->orderByDesc('view_count')
                               ->take(8 - $bestSellers->count())
                               ->get();

            $bestSellers = $bestSellers->concat($fallback);
        }

        // If still less than 4, fill with latest active products
        if ($bestSellers->count() < 4) {
            $excludeIds = $bestSellers->pluck('id')->toArray();
            $latest = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                             ->where('is_active', true)
                             ->whereNotIn('id', $excludeIds)
                             ->orderByDesc('id')
                             ->take(8 - $bestSellers->count())
                             ->get();
            $bestSellers = $bestSellers->concat($latest);
        }

        // Special Discounted Products
        $discountedProducts = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                                     ->where('is_active', true)
                                     ->whereNotNull('discount_price')
                                     ->whereColumn('discount_price', '<', 'price')
                                     ->take(4)
                                     ->get();

        return view('pages.home', compact(
            'heroCampaigns',
            'categories',
            'newArrivals',
            'bestSellers',
            'discountedProducts'
        ));
    }
}
