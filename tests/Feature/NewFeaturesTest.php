<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Size;
use App\Models\StockMovement;
use App\Models\StockNotification;
use App\Models\User;
use App\Services\Payment\PaymentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_sitemap_xml_returns_valid_xml()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'xml'));
        $this->assertStringContainsString('<urlset', $response->getContent());
    }

    public function test_order_tracking_page_accessible()
    {
        $response = $this->get('/siparis-takip');
        $response->assertStatus(200);
        $response->assertSee('Sipariş Takibi');
    }

    public function test_order_tracking_requires_matching_phone()
    {
        $order = Order::create([
            'order_number' => 'YSA-20261007-TEST1',
            'customer_name' => 'Caner Demir',
            'customer_phone' => '05321112233',
            'customer_email' => 'caner@example.com',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Bağdat Cad. No:1',
            'payment_method' => 'kapida_odeme',
            'subtotal' => 1000,
            'shipping_cost' => 0,
            'total_amount' => 1000,
            'status' => 'hazirlaniyor',
            'payment_status' => 'beklemede',
        ]);

        // Wrong phone
        $response = $this->post('/siparis-takip', [
            'order_number' => 'YSA-20261007-TEST1',
            'phone' => '05399999999',
        ]);
        $response->assertSessionHas('error');

        // Correct phone (various formats)
        $responseMatch = $this->post('/siparis-takip', [
            'order_number' => 'YSA-20261007-TEST1',
            'phone' => '+90 (532) 111 22 33',
        ]);
        $responseMatch->assertStatus(200);
        $responseMatch->assertSee('YSA-20261007-TEST1');
        $responseMatch->assertSee('Hazırlanıyor');
    }

    public function test_coupon_percentage_and_fixed_discount_calculations()
    {
        $percentCoupon = Coupon::create([
            'code' => 'VELORA10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 500,
            'maximum_discount_amount' => 200,
            'usage_limit' => 50,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->assertTrue($percentCoupon->isValid(1000));
        $this->assertEquals(100, $percentCoupon->calculateDiscount(1000));

        // Test max discount capping
        $this->assertEquals(200, $percentCoupon->calculateDiscount(3000));

        // Test minimum order not met
        $this->assertFalse($percentCoupon->isValid(400));

        $fixedCoupon = Coupon::create([
            'code' => 'INDIRIM150',
            'type' => 'fixed',
            'value' => 150,
            'minimum_order_amount' => 300,
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->assertTrue($fixedCoupon->isValid(500));
        $this->assertEquals(150, $fixedCoupon->calculateDiscount(500));

        // Fixed coupon discount cannot exceed subtotal
        $this->assertEquals(100, $fixedCoupon->calculateDiscount(100));
    }

    public function test_stock_notification_subscription()
    {
        $product = Product::create([
            'name' => 'Air Jordan High',
            'slug' => 'air-jordan-high',
            'sku' => 'YSA-AJH-001',
            'price' => 4500,
            'gender' => 'erkek',
            'is_active' => true,
        ]);

        $response = $this->postJson('/stok-haber-ver', [
            'product_id' => $product->id,
            'email' => 'ayakkabisever@example.com',
            'phone' => '05551234567',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('stock_notifications', [
            'product_id' => $product->id,
            'email' => 'ayakkabisever@example.com',
            'is_notified' => false,
        ]);

        // Duplicate prevention test
        $dupResponse = $this->postJson('/stok-haber-ver', [
            'product_id' => $product->id,
            'email' => 'ayakkabisever@example.com',
        ]);
        $dupResponse->assertStatus(200);
        $dupResponse->assertJson(['success' => false]);
    }

    public function test_payment_manager_resolution()
    {
        $paymentManager = app(PaymentManager::class);
        $defaultDriver = $paymentManager->driver();
        $this->assertNotNull($defaultDriver);

        $result = $defaultDriver->initialize([
            'amount' => 1000,
            'order_id' => 999,
        ]);

        $this->assertTrue($result['success']);
    }

    public function test_stock_movement_logged_on_order_and_restored_on_cancel()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $product = Product::create([
            'name' => 'Nike Pegasus 40',
            'slug' => 'nike-pegasus-40',
            'sku' => 'YSA-PEGASUS-40',
            'price' => 3200,
            'gender' => 'unisex',
            'is_active' => true,
        ]);

        $size = Size::create([
            'size_number' => '42',
            'gender' => 'unisex',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $stock = ProductSizeStock::create([
            'product_id' => $product->id,
            'size_id' => $size->id,
            'stock' => 10,
        ]);

        // Add to Cart via endpoint
        $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 2,
        ]);

        // Checkout process
        $checkoutResponse = $this->post('/odeme', [
            'customer_name' => 'Burak Kaya',
            'customer_phone' => '05334445566',
            'customer_email' => 'burak@example.com',
            'city' => 'İzmir',
            'district' => 'Konak',
            'address' => 'Alsancak Mah.',
            'payment_method' => 'kapida_odeme',
            'pre_info_approval' => '1',
            'distance_selling_approval' => '1',
        ]);

        $checkoutResponse->assertRedirect();

        // Stock must have decreased from 10 to 8
        $this->assertEquals(8, $stock->fresh()->stock);

        // Stock movement of type 'order' must exist
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'type' => 'order',
            'quantity_before' => 10,
            'quantity_change' => -2,
            'quantity_after' => 8,
        ]);

        $order = Order::where('customer_phone', '05334445566')->first();
        $this->assertNotNull($order);

        // Admin cancels order
        $cancelResponse = $this->actingAs($admin)
                               ->post(route('admin.orders.update-status', $order->id), [
                                   'status' => 'iptal',
                               ]);

        $cancelResponse->assertRedirect();
        $this->assertEquals('iptal', $order->fresh()->status);

        // Stock must be restored to 10
        $this->assertEquals(10, $stock->fresh()->stock);

        // Stock movement of type 'order_cancel' must exist
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'type' => 'order_cancel',
            'quantity_before' => 8,
            'quantity_change' => 2,
            'quantity_after' => 10,
        ]);
    }
}
