<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Services\CartService;
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
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Bind global store data only to root layout views with in-memory request-level memoization
        View::composer(['layouts.app', 'layouts.admin', 'pages.*'], function ($view) {
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
    }
}
