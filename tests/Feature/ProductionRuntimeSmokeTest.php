<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductSizeStock;
use App\Models\ReturnRequest;
use App\Models\Size;
use App\Models\StockMovement;
use App\Models\StockNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionRuntimeSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@velora.test',
        ]);

        $this->customer = User::factory()->create([
            'is_admin' => false,
            'email' => 'customer@velora.test',
        ]);

        $category = Category::create([
            'name' => 'Sneaker',
            'slug' => 'sneaker',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'VELORA Studio',
            'slug' => 'velora-studio',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'VELORA Classic Runner',
            'slug' => 'velora-classic-runner',
            'sku' => 'VLR-001',
            'price' => 1999.00,
            'is_active' => true,
        ]);

        $size = Size::create([
            'size_number' => '42',
            'sort_order' => 1,
        ]);

        ProductSizeStock::create([
            'product_id' => $this->product->id,
            'size_id' => $size->id,
            'stock' => 10,
        ]);

        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'VLR-2026-0001',
            'customer_name' => 'Ahmet Test',
            'customer_phone' => '05550000000',
            'customer_email' => 'customer@velora.test',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Bağdat Cad. No:1',
            'subtotal' => 1999.00,
            'total_amount' => 1999.00,
            'status' => 'yeni',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'size_number' => '42',
            'price' => 1999.00,
            'quantity' => 1,
            'total' => 1999.00,
        ]);

        Coupon::create([
            'code' => 'VELORA10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);

        ProductReview::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'author_name' => 'Ahmet Test',
            'rating' => 5,
            'title' => 'Harika',
            'comment' => 'Çok rahat bir ayakkabı',
            'is_approved' => true,
        ]);

        ReturnRequest::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'user_id' => $this->customer->id,
            'type' => 'return',
            'reason' => 'Beden uymadı',
            'status' => 'bekliyor',
        ]);

        StockMovement::create([
            'product_id' => $this->product->id,
            'size_id' => $size->id,
            'user_id' => $this->admin->id,
            'type' => 'manual_update',
            'quantity_before' => 0,
            'quantity_change' => 10,
            'quantity_after' => 10,
            'reason' => 'Başlangıç stoğu',
        ]);

        StockNotification::create([
            'product_id' => $this->product->id,
            'size_id' => $size->id,
            'email_or_phone' => 'test@velora.test',
            'is_notified' => false,
        ]);
    }

    public function test_all_admin_routes_return_200_ok(): void
    {
        $adminRoutes = [
            '/admin',
            '/admin/products',
            '/admin/products/create',
            '/admin/stocks',
            '/admin/stock-movements',
            '/admin/orders',
            '/admin/returns',
            '/admin/reviews',
            '/admin/reports',
            '/admin/coupons',
            '/admin/coupons/create',
            '/admin/categories',
            '/admin/brands',
            '/admin/campaigns',
            '/admin/messages',
            '/admin/settings',
        ];

        foreach ($adminRoutes as $uri) {
            $response = $this->actingAs($this->admin)->get($uri);
            $response->assertStatus(200, "Admin Route {$uri} failed with status {$response->status()}");
        }
    }

    public function test_all_frontend_routes_return_200_ok(): void
    {
        $frontendRoutes = [
            '/',
            '/urunler',
            '/sepet',
            '/odeme',
            '/siparis-takip',
            '/giris-yap',
            '/kayit-ol',
            '/hakkimizda',
            '/iletisim',
            '/beden-rehberi',
            '/kvkk-aydinlatma-metni',
            '/gizlilik-politikasi',
            '/cerez-politikasi',
            '/mesafeli-satis-sozlesmesi',
            '/on-bilgilendirme-formu',
            '/iade-ve-degisim-politikasi',
            '/urun/' . $this->product->slug,
        ];

        foreach ($frontendRoutes as $uri) {
            if ($uri === '/odeme') {
                $response = $this->get($uri);
                $response->assertRedirect('/sepet');
            } else {
                $response = $this->get($uri);
                $response->assertStatus(200, "Frontend Route {$uri} failed with status {$response->status()}");
            }
        }
    }

    public function test_authenticated_customer_portal_routes_return_200_ok(): void
    {
        $portalRoutes = [
            '/hesabim',
            '/hesabim/siparislerim',
            '/hesabim/adreslerim',
            '/hesabim/profilim',
        ];

        foreach ($portalRoutes as $uri) {
            $response = $this->actingAs($this->customer)->get($uri);
            $response->assertStatus(200, "Customer Portal Route {$uri} failed with status {$response->status()}");
        }
    }
}
