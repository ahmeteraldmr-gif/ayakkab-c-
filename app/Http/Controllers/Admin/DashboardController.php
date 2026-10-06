<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSizeStock;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Primary Metrics
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('status', ['yeni', 'hazirlaniyor'])->count();
        $completedOrders = Order::where('status', 'tamamlandi')->count();
        $totalRevenue = (float) Order::where('status', '!=', 'iptal')->sum('total_amount');

        // 2. Extra Financial & Operational Statistics
        $todayRevenue = (float) Order::whereDate('created_at', today())->where('status', '!=', 'iptal')->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        $monthRevenue = (float) Order::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->where('status', '!=', 'iptal')
                                     ->sum('total_amount');

        $last7DaysOrders = Order::where('created_at', '>=', now()->subDays(6)->startOfDay())->count();
        
        $avgOrderValue = (float) (Order::where('status', '!=', 'iptal')->avg('total_amount') ?? 0);

        // Best Selling Product
        $topProductItem = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_sales'))
                                   ->groupBy('product_id')
                                   ->orderByDesc('total_qty')
                                   ->with('product.brand')
                                   ->first();

        // Best Selling Category
        $topCategory = Category::join('products', 'categories.id', '=', 'products.category_id')
                               ->join('order_items', 'products.id', '=', 'order_items.product_id')
                               ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_qty'))
                               ->groupBy('categories.id', 'categories.name')
                               ->orderByDesc('total_qty')
                               ->first();

        // 3. Stock Health
        $lowStockCount = ProductSizeStock::where('stock', '<=', 3)->where('stock', '>', 0)->count();
        $outOfStockCount = ProductSizeStock::where('stock', '<=', 0)->count();

        // 4. Unread Messages
        $unreadMessagesCount = ContactMessage::where('is_read', false)->count();

        // 5. Recent 6 Orders
        $recentOrders = Order::with('items')
                             ->orderByDesc('id')
                             ->take(6)
                             ->get();

        // 6. Low stock items list (prioritizing 0 stock then 1-3)
        $lowStockItems = ProductSizeStock::with(['product.brand', 'size'])
                                         ->where('stock', '<=', 3)
                                         ->whereHas('product')
                                         ->orderBy('stock')
                                         ->take(8)
                                         ->get();

        // 7. Last 7 Days Sales Trend Data for Chart.js
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        $turkishMonths = [
            1 => 'Oca', 2 => 'Şub', 3 => 'Mar', 4 => 'Nis', 5 => 'May', 6 => 'Haz',
            7 => 'Tem', 8 => 'Ağu', 9 => 'Eyl', 10 => 'Eki', 11 => 'Kas', 12 => 'Ara'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dayNum = $date->format('j');
            $monthNum = (int) $date->format('n');
            $label = $dayNum . ' ' . ($turkishMonths[$monthNum] ?? '');

            $dayRevenue = (float) Order::whereDate('created_at', $dateStr)
                                       ->where('status', '!=', 'iptal')
                                       ->sum('total_amount');
                                       
            $dayOrdersCount = (int) Order::whereDate('created_at', $dateStr)->count();

            $chartLabels[] = $label;
            $chartRevenue[] = round($dayRevenue, 2);
            $chartOrders[] = $dayOrdersCount;
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'todayRevenue',
            'todayOrders',
            'monthRevenue',
            'last7DaysOrders',
            'avgOrderValue',
            'topProductItem',
            'topCategory',
            'lowStockCount',
            'outOfStockCount',
            'unreadMessagesCount',
            'recentOrders',
            'lowStockItems',
            'chartLabels',
            'chartRevenue',
            'chartOrders'
        ));
    }
}
