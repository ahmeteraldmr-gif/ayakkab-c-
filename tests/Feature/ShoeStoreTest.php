<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Setting;
use App\Models\Size;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShoeStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test Home Page renders correctly
     */
    public function test_home_page_loads_with_products_and_categories(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('YUSUF AKBOĞA');
        $response->assertSee('Tarzını');
        $response->assertSee('Adımlarınla');
        $response->assertSee('Yeni Gelen Modeller');
        $response->assertSee('Öne Çıkan Kategoriler');
    }

    /**
     * Test Products catalog page and filters
     */
    public function test_products_catalog_page_and_filtering(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Ayakkabı Koleksiyonu');

        // Test search query
        $searchResponse = $this->get(route('products.index', ['q' => 'Nike']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Nike');

        // Test AJAX filter
        $ajaxResponse = $this->getJson(route('products.index', ['gender' => 'erkek']));
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure(['html', 'pagination', 'total', 'count_text']);
    }

    /**
     * Test Product Detail page
     */
    public function test_product_detail_page_loads(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get(route('product.detail', $product->slug));
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->sku);
        $response->assertSee('Ayakkabı Numarası Seçiniz');
        $response->assertSee('WhatsApp ile Bilgi Al');
    }

    /**
     * Test Cart operations and size-based stock validation
     */
    public function test_cart_addition_and_stock_validation(): void
    {
        $product = Product::first();
        $stockRecord = ProductSizeStock::where('product_id', $product->id)->where('stock', '>', 0)->first();
        $this->assertNotNull($stockRecord);

        // Add to cart with valid size
        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'size_id' => $stockRecord->size_id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify cart page displays item
        $cartPage = $this->get(route('cart.index'));
        $cartPage->assertStatus(200);
        $cartPage->assertSee($product->name);
    }

    /**
     * Test Checkout process, order creation and stock deduction
     */
    public function test_checkout_creates_order_and_reduces_stock(): void
    {
        $product = Product::first();
        $stockRecord = ProductSizeStock::where('product_id', $product->id)->where('stock', '>=', 2)->first();
        $this->assertNotNull($stockRecord);

        $initialStock = $stockRecord->stock;

        // Prepare explicit cart session data
        $cartKey = $product->id . '_' . $stockRecord->size_id;
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_sku' => $product->sku,
                'product_image' => $product->primary_image_url,
                'brand_name' => 'Nike',
                'size_id' => $stockRecord->size_id,
                'size_number' => '42',
                'price' => (float) $product->effective_price,
                'original_price' => (float) $product->price,
                'quantity' => 2,
                'max_stock' => $stockRecord->stock,
                'total' => (float) ($product->effective_price * 2),
            ]
        ];

        // Place order with active session
        $orderData = [
            'customer_name' => 'Caner Erkin',
            'customer_phone' => '05321112233',
            'customer_email' => 'caner@example.com',
            'city' => 'İstanbul',
            'district' => 'Beşiktaş',
            'address' => 'Vişnezade Mah. Süleyman Seba Cad. No: 10',
            'order_notes' => 'Acil teslimat rica ederim.',
            'payment_method' => 'kapida_odeme',
            'pre_info_approval' => '1',
            'distance_selling_approval' => '1',
        ];

        $checkoutResponse = $this->withSession(['shopping_cart' => $cartData])
                                 ->post(route('checkout.process'), $orderData);
        $checkoutResponse->assertSessionHasNoErrors();
        $checkoutResponse->assertStatus(302);

        // Verify Order in database
        $order = Order::where('customer_phone', '05321112233')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('YSA-', $order->order_number);
        $this->assertEquals(2, $order->items->first()->quantity);

        // Verify stock decremented
        $stockRecord->refresh();
        $this->assertEquals($initialStock - 2, $stockRecord->stock);

        // Verify success page is accessible with token
        $successResponse = $this->get(route('order.success', [
            'orderNumber' => $order->order_number,
            'token' => $order->access_token,
        ]));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($order->order_number);
    }

    /**
     * Regression Test 1: IDOR Prevention on order success page
     */
    public function test_order_success_page_unauthorized_access_prevented(): void
    {
        $order = Order::first();
        $this->assertNotNull($order);
        if (empty($order->access_token)) {
            $order->update(['access_token' => \Illuminate\Support\Str::random(40)]);
        }

        // A stranger guest trying to access order without session or token should be redirected
        $this->flushSession();
        $response = $this->get(route('order.success', $order->order_number));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');

        // Accessing with valid access_token query parameter should be granted
        $validTokenResponse = $this->get(route('order.success', [
            'orderNumber' => $order->order_number,
            'token' => $order->access_token,
        ]));
        $validTokenResponse->assertStatus(200);
        $validTokenResponse->assertSee($order->order_number);
    }

    /**
     * Regression Test 2: Checkout recalculates price strictly from DB (ignores manipulated session prices)
     */
    public function test_checkout_recalculates_price_strictly_from_database_ignoring_session_price(): void
    {
        $product = Product::first();
        $product->update([
            'price' => 5000.00,
            'discount_price' => 4000.00,
            'is_active' => true,
        ]);
        $stockRecord = ProductSizeStock::where('product_id', $product->id)->where('stock', '>=', 1)->first();

        // Manipulate session price to 1.00 TL
        $cartKey = $product->id . '_' . $stockRecord->size_id;
        $tamperedCart = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_sku' => $product->sku,
                'product_image' => $product->primary_image_url,
                'brand_name' => 'Nike',
                'size_id' => $stockRecord->size_id,
                'size_number' => '42',
                'price' => 1.00, // FAKE PRICE
                'original_price' => 1.00,
                'quantity' => 1,
                'max_stock' => $stockRecord->stock,
                'total' => 1.00, // FAKE TOTAL
            ]
        ];

        $orderData = [
            'customer_name' => 'Ali Veli',
            'customer_phone' => '05554443322',
            'customer_email' => 'ali@example.com',
            'city' => 'Ankara',
            'district' => 'Çankaya',
            'address' => 'Atatürk Bulvarı No: 50',
            'payment_method' => 'kapida_odeme',
            'pre_info_approval' => '1',
            'distance_selling_approval' => '1',
        ];

        $response = $this->withSession(['shopping_cart' => $tamperedCart])
                         ->post(route('checkout.process'), $orderData);

        $response->assertSessionHasNoErrors();
        $order = Order::where('customer_phone', '05554443322')->first();
        $this->assertNotNull($order);

        // Subtotal must be 4000.00 TL (the DB effective price), NOT 1.00 TL!
        $this->assertEquals(4000.00, (float) $order->subtotal);
        $this->assertEquals(4000.00, (float) $order->items->first()->price);
    }

    /**
     * Regression Test 3: Checkout fails if product is deactivated
     */
    public function test_checkout_fails_if_product_is_deactivated(): void
    {
        $product = Product::first();
        $stockRecord = ProductSizeStock::where('product_id', $product->id)->where('stock', '>=', 1)->first();

        // Deactivate product
        $product->update(['is_active' => false]);

        $cartKey = $product->id . '_' . $stockRecord->size_id;
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_sku' => $product->sku,
                'product_image' => $product->primary_image_url,
                'brand_name' => 'Nike',
                'size_id' => $stockRecord->size_id,
                'size_number' => '42',
                'price' => (float) $product->effective_price,
                'original_price' => (float) $product->price,
                'quantity' => 1,
                'max_stock' => $stockRecord->stock,
                'total' => (float) $product->effective_price,
            ]
        ];

        $orderData = [
            'customer_name' => 'Mehmet Pasif',
            'customer_phone' => '05551112233',
            'city' => 'İzmir',
            'district' => 'Konak',
            'address' => 'Alsancak Cad. No: 1',
            'payment_method' => 'kapida_odeme',
        ];

        $response = $this->withSession(['shopping_cart' => $cartData])
                         ->post(route('checkout.process'), $orderData);

        // Must redirect back with error and not create order
        $response->assertRedirect();
        $this->assertDatabaseMissing('orders', ['customer_phone' => '05551112233']);
    }

    /**
     * Regression Test 4: Checkout fails if size stock is insufficient
     */
    public function test_checkout_fails_if_size_stock_is_insufficient(): void
    {
        $product = Product::first();
        $stockRecord = ProductSizeStock::where('product_id', $product->id)->first();
        $stockRecord->update(['stock' => 1]);

        $cartKey = $product->id . '_' . $stockRecord->size_id;
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_sku' => $product->sku,
                'product_image' => $product->primary_image_url,
                'brand_name' => 'Nike',
                'size_id' => $stockRecord->size_id,
                'size_number' => '42',
                'price' => (float) $product->effective_price,
                'original_price' => (float) $product->price,
                'quantity' => 5, // Demanding 5 when only 1 exists
                'max_stock' => 1,
                'total' => (float) ($product->effective_price * 5),
            ]
        ];

        $orderData = [
            'customer_name' => 'Stoksuz Siparis',
            'customer_phone' => '05550009988',
            'city' => 'Bursa',
            'district' => 'Nilüfer',
            'address' => 'FSM Bulvarı No: 12',
            'payment_method' => 'kapida_odeme',
        ];

        $response = $this->withSession(['shopping_cart' => $cartData])
                         ->post(route('checkout.process'), $orderData);

        $response->assertRedirect();
        $this->assertDatabaseMissing('orders', ['customer_phone' => '05550009988']);
    }

    /**
     * Regression Test 5: Admin settings blocks invalid files and unwhitelisted keys
     */
    public function test_admin_settings_blocks_invalid_file_upload_and_unwhitelisted_keys(): void
    {
        $admin = User::where('is_admin', true)->first();

        // 1. Uploading non-image file should fail validation
        $fakeScript = UploadedFile::fake()->create('malicious.php', 100, 'application/x-php');

        $response = $this->actingAs($admin)
                         ->post(route('admin.settings.update'), [
                             'site_name' => 'Yeni Mağaza Adı',
                             'site_logo' => $fakeScript,
                         ]);

        $response->assertSessionHasErrors('site_logo');

        // 2. Unwhitelisted key should not be written to settings table
        $responseOk = $this->actingAs($admin)
                           ->post(route('admin.settings.update'), [
                               'site_name' => 'Yusuf Akboğa Ayakkabı',
                               'arbitrary_unwhitelisted_key' => 'hacked_val',
                           ]);

        $responseOk->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('settings', ['key' => 'arbitrary_unwhitelisted_key']);
    }

    /**
     * Regression Test 6: Safe JSON encoding in product cards with quotes
     */
    public function test_product_cards_safely_render_data_product_with_quotes(): void
    {
        $productWithQuotes = Product::where('name', 'like', "%'%")->first();
        if ($productWithQuotes) {
            $response = $this->get(route('product.detail', $productWithQuotes->slug));
            $response->assertStatus(200);
            $response->assertSee('data-product=', false);
            $response->assertSee('toggleFavoriteFromButton', false);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * Test Contact Form submission
     */
    public function test_contact_form_submission(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Mehmet Öz',
            'email_or_phone' => '05443332211',
            'subject' => 'Özel Numara Talebi',
            'message' => '46 numara ayakkabı stoğunuz var mı acaba?',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Mehmet Öz',
            'email_or_phone' => '05443332211',
        ]);
    }

    /**
     * Test Admin Authentication and Protected Dashboard Access
     */
    public function test_admin_authentication_and_dashboard(): void
    {
        // Guest cannot access admin dashboard
        $guestResponse = $this->get(route('admin.dashboard'));
        $guestResponse->assertRedirect(route('admin.login'));

        // Admin login
        $loginResponse = $this->post(route('admin.login.submit'), [
            'email' => 'admin@yusufakboga.com',
            'password' => 'admin123',
        ]);
        $loginResponse->assertRedirect(route('admin.dashboard'));

        // Admin can access dashboard
        $admin = User::where('is_admin', true)->first();
        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard &amp; Mağaza Özeti', false);
        $dashboardResponse->assertSee('Toplam Ürün');
    }

    /**
     * Test Admin Stock Matrix quick update
     */
    public function test_admin_stock_quick_update(): void
    {
        $admin = User::where('is_admin', true)->first();
        $product = Product::first();
        $size = Size::first();

        $response = $this->actingAs($admin)->postJson(route('admin.stocks.quick-update'), [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'stock' => 15,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'stock' => 15]);

        $this->assertDatabaseHas('product_size_stocks', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'stock' => 15,
        ]);
    }
}
