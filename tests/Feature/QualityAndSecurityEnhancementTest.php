<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QualityAndSecurityEnhancementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test Security Headers are present on responses
     */
    public function test_security_headers_are_attached_to_responses(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Test Custom 404 page renders beautifully
     */
    public function test_custom_404_page_renders_with_home_action(): void
    {
        $response = $this->get('/non-existent-shoe-route-12345');

        $response->assertStatus(404);
        $response->assertSee('Aradığınız Sayfa Bulunamadı');
        $response->assertSee('Ana Sayfaya Dön');
    }

    /**
     * Test Order Status Transition Machine rules
     */
    public function test_order_state_machine_prevents_invalid_transitions(): void
    {
        $admin = User::where('is_admin', true)->first();
        $this->assertNotNull($admin);

        $order = Order::create([
            'order_number' => 'YSA-999999',
            'customer_name' => 'State Test',
            'customer_phone' => '05551234567',
            'customer_email' => 'test@test.com',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Test Adres',
            'subtotal' => 1000,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total_amount' => 1000,
            'status' => 'tamamlandi',
            'payment_method' => 'kapida_odeme',
            'payment_status' => 'paid',
        ]);

        // Trying to move completed order back to 'yeni' should fail
        $response = $this->actingAs($admin)->post(route('admin.orders.update-status', $order->id), [
            'status' => 'yeni',
        ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('tamamlandi', $order->status);
    }

    /**
     * Test Audit Log is recorded when Admin modifies orders
     */
    public function test_audit_log_is_recorded_on_admin_actions(): void
    {
        $admin = User::where('is_admin', true)->first();
        $this->assertNotNull($admin);

        $order = Order::create([
            'order_number' => 'YSA-888888',
            'customer_name' => 'Audit Test',
            'customer_phone' => '05551234567',
            'customer_email' => 'audit@test.com',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Test Adres',
            'subtotal' => 1000,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total_amount' => 1000,
            'status' => 'yeni',
            'payment_method' => 'kapida_odeme',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.orders.update-status', $order->id), [
            'status' => 'hazirlaniyor',
        ]);

        $log = AuditLog::where('action', 'order_status_updated')->where('model_id', $order->id)->first();
        $this->assertNotNull($log);
        $this->assertStringContainsString('YSA-888888', $log->description);
    }

    /**
     * Test SEO, Open Graph and Twitter Card tags render on product pages
     */
    public function test_seo_and_open_graph_tags_render_on_product_page(): void
    {
        $product = Product::first();
        $response = $this->get(route('product.detail', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('rel="canonical"', false);
    }
}
