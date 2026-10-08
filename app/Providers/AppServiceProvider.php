<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\ProductSizeStock;
use App\Models\StockNotification;
use App\Services\CartService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 1. Bind global store data to customer layouts
        View::composer(['layouts.app', 'pages.*'], function ($view) {
            try {
                static $cachedCategories = null;
                static $cachedBrands = null;

                if ($cachedCategories === null) {
                    $cachedCategories = Category::where('is_active', true)->orderBy('sort_order')->get();
                }

                if ($cachedBrands === null) {
                    $cachedBrands = Brand::where('is_active', true)->orderBy('name')->get();
                }

                $cartService = app(CartService::class);
                $cartSummary = $cartService->getSummary();

                $view->with([
                    'globalCategories' => $cachedCategories,
                    'globalBrands' => $cachedBrands,
                    'globalCart' => $cartSummary,
                ]);
            } catch (\Throwable $e) {
                // Ignore during migrations / early bootstrap
            }
        });

        // 2. Admin Notification Center Composer
        View::composer('layouts.admin', function ($view) {
            try {
                static $adminNotificationData = null;

                if ($adminNotificationData === null) {
                    $newOrders = \Illuminate\Support\Facades\Schema::hasTable('orders') ? Order::where('status', 'yeni')->count() : 0;
                    $unreadMessages = \Illuminate\Support\Facades\Schema::hasTable('contact_messages') ? ContactMessage::where('is_read', false)->count() : 0;
                    $lowStock = \Illuminate\Support\Facades\Schema::hasTable('product_size_stocks') ? ProductSizeStock::where('stock', '<=', 3)->count() : 0;
                    $pendingAlerts = \Illuminate\Support\Facades\Schema::hasTable('stock_notifications') ? StockNotification::where('is_notified', false)->count() : 0;

                    $adminNotificationData = [
                        'adminNewOrdersCount' => $newOrders,
                        'adminUnreadMessagesCount' => $unreadMessages,
                        'adminLowStockCount' => $lowStock,
                        'adminPendingAlertsCount' => $pendingAlerts,
                        'adminTotalNotifications' => ($newOrders + $unreadMessages + $pendingAlerts),
                    ];
                }

                $view->with($adminNotificationData);
            } catch (\Throwable $e) {
                $view->with([
                    'adminNewOrdersCount' => 0,
                    'adminUnreadMessagesCount' => 0,
                    'adminLowStockCount' => 0,
                    'adminPendingAlertsCount' => 0,
                    'adminTotalNotifications' => 0,
                ]);
            }
        });
    }
}
