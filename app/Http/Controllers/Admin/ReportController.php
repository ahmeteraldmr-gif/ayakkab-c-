<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display Advanced Sales & Store Performance Reports
     */
    public function index(Request $request): View
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : now()->subDays(29)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : now()->endOfDay();
        $brandId = $request->query('brand_id');
        $categoryId = $request->query('category_id');
        $productId = $request->query('product_id');

        // Base Orders Query in date range
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $totalOrdersCount = (clone $ordersQuery)->count();
        $cancelledOrdersCount = (clone $ordersQuery)->where('status', 'iptal')->count();
        $completedOrdersCount = (clone $ordersQuery)->where('status', 'tamamlandi')->count();

        $cancellationRate = $totalOrdersCount > 0 ? round(($cancelledOrdersCount / $totalOrdersCount) * 100, 1) : 0;

        $returnCount = ReturnRequest::whereBetween('created_at', [$startDate, $endDate])->where('status', 'tamamlandi')->count();
        $returnRate = $completedOrdersCount > 0 ? round(($returnCount / $completedOrdersCount) * 100, 1) : 0;

        // Base Order Items Query (excluding cancelled orders)
        $itemsQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                               ->where('orders.status', '!=', 'iptal')
                               ->whereBetween('orders.created_at', [$startDate, $endDate]);

        if ($productId) {
            $itemsQuery->where('order_items.product_id', $productId);
        }
        if ($categoryId || $brandId) {
            $itemsQuery->join('products', 'order_items.product_id', '=', 'products.id');
            if ($categoryId) {
                $itemsQuery->where('products.category_id', $categoryId);
            }
            if ($brandId) {
                $itemsQuery->where('products.brand_id', $brandId);
            }
        }

        $totalUnitsSold = (int) (clone $itemsQuery)->sum('order_items.quantity');
        $totalRevenue = (float) (clone $itemsQuery)->sum('order_items.total');
        $activeOrdersCount = (clone $ordersQuery)->where('status', '!=', 'iptal')->count();
        $avgOrderValue = $activeOrdersCount > 0 ? round($totalRevenue / $activeOrdersCount, 2) : 0;

        // Top Selling Product
        $topProduct = (clone $itemsQuery)
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.total) as rev'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->first();

        // Top Selling Size
        $topSize = (clone $itemsQuery)
            ->select('order_items.size_number', DB::raw('SUM(order_items.quantity) as qty'))
            ->groupBy('order_items.size_number')
            ->orderByDesc('qty')
            ->first();

        // Top Selling Brand
        $topBrand = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                             ->join('products', 'order_items.product_id', '=', 'products.id')
                             ->join('brands', 'products.brand_id', '=', 'brands.id')
                             ->where('orders.status', '!=', 'iptal')
                             ->whereBetween('orders.created_at', [$startDate, $endDate])
                             ->select('brands.name', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.total) as rev'))
                             ->groupBy('brands.name')
                             ->orderByDesc('rev')
                             ->first();

        // Top Selling Category
        $topCategory = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->join('products', 'order_items.product_id', '=', 'products.id')
                                ->join('categories', 'products.category_id', '=', 'categories.id')
                                ->where('orders.status', '!=', 'iptal')
                                ->whereBetween('orders.created_at', [$startDate, $endDate])
                                ->select('categories.name', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.total) as rev'))
                                ->groupBy('categories.name')
                                ->orderByDesc('rev')
                                ->first();

        // Daily Trend Data for Chart.js
        $dailyData = Order::where('status', '!=', 'iptal')
                          ->whereBetween('created_at', [$startDate, $endDate])
                          ->select(
                              DB::raw('DATE(created_at) as date_val'),
                              DB::raw('SUM(total_amount) as daily_revenue'),
                              DB::raw('COUNT(*) as daily_orders')
                          )
                          ->groupBy('date_val')
                          ->orderBy('date_val')
                          ->get()
                          ->keyBy('date_val');

        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        $period = Carbon::parse($startDate)->toPeriod($endDate);
        foreach ($period as $dt) {
            $key = $dt->format('Y-m-d');
            $chartLabels[] = $dt->format('d M');
            $chartRevenue[] = isset($dailyData[$key]) ? (float) $dailyData[$key]->daily_revenue : 0;
            $chartOrders[] = isset($dailyData[$key]) ? (int) $dailyData[$key]->daily_orders : 0;
        }

        $topSellingProducts = (clone $itemsQuery)
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.total) as total_revenue'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $metrics = [
            'total_revenue' => $totalRevenue,
            'delivered_orders' => $completedOrdersCount,
            'total_orders' => $totalOrdersCount,
            'units_sold' => $totalUnitsSold,
            'cancellation_rate' => $cancellationRate,
            'return_rate' => $returnRate,
            'avg_order_value' => $avgOrderValue,
        ];

        $chartValues = $chartRevenue;

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'brandId',
            'categoryId',
            'productId',
            'metrics',
            'topSellingProducts',
            'chartLabels',
            'chartValues',
            'chartRevenue',
            'chartOrders',
            'categories',
            'brands',
            'products'
        ));
    }

    /**
     * Export Filtered Report Data to CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : now()->subDays(29)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : now()->endOfDay();

        $fileName = 'velora_satis_raporu_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'Siparis No',
                'Tarih',
                'Musteri Adi',
                'Telefon',
                'Sehir',
                'Urun Adi',
                'Numara',
                'Adet',
                'Birim Fiyat (TL)',
                'Toplam Tutar (TL)',
                'Siparis Durumu',
                'Odeme Yontemi',
            ], ';');

            $orderItems = OrderItem::with('order')
                                   ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                                   ->get();

            $escapeCsv = function ($value) {
                if (is_string($value)) {
                    $trimmed = trim($value);
                    if ($trimmed !== '' && in_array($trimmed[0], ['=', '+', '-', '@', "\t", "\r"])) {
                        return "'" . $value;
                    }
                }
                return $value;
            };

            foreach ($orderItems as $item) {
                $order = $item->order;
                fputcsv($handle, [
                    $escapeCsv($order->order_number ?? ''),
                    $escapeCsv($order->created_at ? $order->created_at->format('d.m.Y H:i') : ''),
                    $escapeCsv($order->customer_name ?? ''),
                    $escapeCsv($order->customer_phone ?? ''),
                    $escapeCsv($order->city ?? ''),
                    $escapeCsv($item->product_name ?? ''),
                    $escapeCsv($item->size_number ?? ''),
                    $item->quantity ?? 1,
                    number_format($item->price, 2, ',', ''),
                    number_format($item->total, 2, ',', ''),
                    $escapeCsv($order->status_label ?? $order->status),
                    $escapeCsv($order->payment_method ?? ''),
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
