<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReturnRequestController as AdminReturnRequestController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\StockMovementController as AdminStockMovementController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Customer\PortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\OrderTrackController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StockNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Frontend)
|--------------------------------------------------------------------------
*/

// XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products & Catalog
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/api/search', [ProductController::class, 'search'])->middleware('throttle:60,1')->name('api.search');

// Shopping Cart
Route::get('/sepet', [CartController::class, 'index'])->name('cart.index');
Route::post('/sepet/ekle', [CartController::class, 'add'])->name('cart.add');
Route::post('/sepet/guncelle', [CartController::class, 'update'])->name('cart.update');
Route::post('/sepet/sil', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/sepet/temizle', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/api/cart/summary', [CartController::class, 'summary'])->name('api.cart.summary');

// Coupon Frontend API & Web
Route::post('/kupon/uygula', [CouponController::class, 'apply'])->middleware('throttle:20,1')->name('coupon.apply');
Route::post('/kupon/kaldir', [CouponController::class, 'remove'])->name('coupon.remove');
Route::post('/api/coupon/apply', [CouponController::class, 'apply'])->middleware('throttle:20,1')->name('api.coupon.apply');
Route::post('/api/coupon/remove', [CouponController::class, 'remove'])->name('api.coupon.remove');

// Product Review Submission
Route::post('/urun/{product}/yorum-yap', [ProductReviewController::class, 'store'])->middleware('throttle:5,1')->name('reviews.store');

// Stock Notification API (Stokta Yok - Haber Ver)
Route::post('/stok-haber-ver', [StockNotificationController::class, 'store'])->middleware('throttle:10,1')->name('stock.notify');
Route::post('/api/stock-notification', [StockNotificationController::class, 'store'])->middleware('throttle:10,1')->name('api.stock-notification');

// Order Tracking (Sipariş Takip)
Route::get('/siparis-takip', [OrderTrackController::class, 'index'])->name('order.track.index');
Route::post('/siparis-takip', [OrderTrackController::class, 'track'])->middleware('throttle:10,1')->name('order.track.submit');

// Checkout & Orders
Route::get('/odeme', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/odeme', [CheckoutController::class, 'process'])->middleware('throttle:10,1')->name('checkout.process');
Route::get('/siparis-basarili/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
Route::any('/odeme/callback/{driver?}', [CheckoutController::class, 'paymentCallback'])->name('payment.callback');

// Customer Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/giris-yap', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/giris-yap', [CustomerAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::get('/kayit-ol', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/kayit-ol', [CustomerAuthController::class, 'register'])->middleware('throttle:5,1')->name('register.submit');
    Route::get('/sifremi-unuttum', [CustomerAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/sifremi-unuttum', [CustomerAuthController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/sifre-sifirla/{token}', [CustomerAuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/sifre-sifirla', [CustomerAuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});
Route::match(['get', 'post'], '/cikis-yap', [CustomerAuthController::class, 'logout'])->name('logout');

// Customer Portal Routes
Route::middleware('auth')->prefix('hesabim')->name('customer.')->group(function () {
    Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/siparislerim', [PortalController::class, 'orders'])->name('orders');
    Route::get('/siparislerim/{orderNumber}', [PortalController::class, 'orderDetail'])->name('orders.show');
    Route::get('/adreslerim', [PortalController::class, 'addresses'])->name('addresses');
    Route::post('/adres-ekle', [PortalController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/adres-guncelle/{address}', [PortalController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/adres-sil/{address}', [PortalController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::post('/adres-varsayilan/{address}', [PortalController::class, 'setDefaultAddress'])->name('addresses.set-default');
    Route::get('/profilim', [PortalController::class, 'profile'])->name('profile');
    Route::put('/profil-guncelle', [PortalController::class, 'updateProfile'])->name('profile.update');
    Route::put('/sifre-guncelle', [PortalController::class, 'updatePassword'])->name('password.update');
    Route::post('/iade-talebi', [PortalController::class, 'submitReturnRequest'])->name('returns.store');
});

// Legal Pages
Route::get('/kvkk-aydinlatma-metni', [LegalPageController::class, 'kvkk'])->name('legal.kvkk');
Route::get('/gizlilik-politikasi', [LegalPageController::class, 'privacy'])->name('legal.privacy');
Route::get('/cerez-politikasi', [LegalPageController::class, 'cookies'])->name('legal.cookies');
Route::get('/mesafeli-satis-sozlesmesi', [LegalPageController::class, 'distanceSelling'])->name('legal.distance-selling');
Route::get('/on-bilgilendirme-formu', [LegalPageController::class, 'preInfo'])->name('legal.pre-info');
Route::get('/iade-ve-degisim-politikasi', [LegalPageController::class, 'returnPolicy'])->name('legal.return-policy');

// Static Pages & Contact
Route::get('/hakkimizda', [PageController::class, 'about'])->name('about');
Route::get('/iletisim', [PageController::class, 'contact'])->name('contact');
Route::post('/iletisim', [PageController::class, 'contactSubmit'])->middleware('throttle:5,1')->name('contact.submit');
Route::get('/beden-rehberi', [PageController::class, 'sizeGuide'])->name('size.guide');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::match(['get', 'post'], '/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class);
    Route::delete('products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::post('products/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.set-primary-image');

    // Stock Management & Stock Movements
    Route::get('stocks', [AdminStockController::class, 'index'])->name('stocks.index');
    Route::get('stock-movements', [AdminStockMovementController::class, 'index'])->name('stocks.movements');
    Route::post('stocks/bulk-update', [AdminStockController::class, 'bulkUpdate'])->name('stocks.bulk-update');
    Route::post('stocks/quick-update', [AdminStockController::class, 'quickUpdate'])->name('stocks.quick-update');

    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Reviews Management
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Return & Exchange Requests
    Route::get('returns', [AdminReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('returns/{returnRequest}', [AdminReturnRequestController::class, 'show'])->name('returns.show');
    Route::post('returns/{returnRequest}/status', [AdminReturnRequestController::class, 'updateStatus'])->name('returns.update-status');

    // Reports
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.export-csv');

    // Coupons Management
    Route::resource('coupons', AdminCouponController::class);

    // Categories & Brands
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('brands', AdminBrandController::class);

    // Campaigns / Banners
    Route::resource('campaigns', AdminCampaignController::class);

    // Messages
    Route::get('messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{message}/read', [AdminMessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // Site Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
