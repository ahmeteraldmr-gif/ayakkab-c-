<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
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

        // Best Sellers / Featured (Çok Satanlar & Öne Çıkanlar)
        $bestSellers = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                              ->where('is_active', true)
                              ->where(function ($q) {
                                  $q->where('is_featured', true)
                                    ->orWhere('view_count', '>', 5);
                              })
                              ->orderByDesc('view_count')
                              ->take(8)
                              ->get();

        // If best sellers count is less than 4, fallback to latest products
        if ($bestSellers->count() < 4) {
            $bestSellers = Product::with(['brand', 'images', 'sizes', 'sizeStocks'])
                                  ->where('is_active', true)
                                  ->orderByDesc('id')
                                  ->take(8)
                                  ->get();
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
