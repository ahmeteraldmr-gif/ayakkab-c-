<?php

namespace Tests\Feature;

use App\Mail\StockAvailableNotificationMail;
use App\Models\Coupon;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\ReturnRequest;
use App\Models\Size;
use App\Models\StockNotification;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PayTrPaymentService;
use App\Services\StockNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProductionAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * 1. IDOR Customer Orders: User cannot view another customer's order.
     */
    public function test_idor_customer_cannot_view_another_users_order(): void
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $order1 = Order::create([
            'order_number' => 'YSA-2026-111111',
            'user_id' => $user1->id,
            'customer_name' => $user1->name,
            'customer_phone' => '05321111111',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Test Adres',
            'subtotal' => 1000,
            'total_amount' => 1000,
            'status' => 'yeni',
            'payment_method' => 'kapida_odeme',
        ]);

        // User 2 tries to view User 1's order via URL
        $this->actingAs($user2);
        $response = $this->get(route('customer.orders.show', $order1->order_number));
        $response->assertStatus(404);
    }

    /**
     * 2. Address Ownership IDOR: User cannot update or delete another customer's address.
     */
    public function test_address_ownership_idor_protection(): void
    {
        $user1 = User::create(['name' => 'Owner', 'email' => 'owner_' . uniqid() . '@example.com', 'password' => Hash::make('secret123')]);
        $user2 = User::create(['name' => 'Attacker', 'email' => 'attacker_' . uniqid() . '@example.com', 'password' => Hash::make('secret123')]);

        $address = CustomerAddress::create([
            'user_id' => $user1->id,
            'title' => 'Ev',
            'full_name' => 'Owner User',
            'phone' => '05321112233',
            'city' => 'Ankara',
            'district' => 'Çankaya',
            'address' => 'Gizli Adres',
            'is_default' => true,
        ]);

        $this->actingAs($user2);

        // Try update
        $updateResp = $this->put(route('customer.addresses.update', $address->id), [
            'title' => 'Hacked',
            'full_name' => 'Attacker',
            'phone' => '05320000000',
            'city' => 'İzmir',
            'district' => 'Konak',
            'address' => 'Hacked Address',
        ]);
        $updateResp->assertStatus(403);
        $this->assertEquals('Ev', $address->fresh()->title);

        // Try delete
        $delResp = $this->delete(route('customer.addresses.destroy', $address->id));
        $delResp->assertStatus(403);
        $this->assertDatabaseHas('customer_addresses', ['id' => $address->id]);
    }

    /**
     * 3. Return Request Item Ownership & Duplicate Prevention.
     */
    public function test_return_request_item_ownership_and_duplicate_prevention(): void
    {
        $user1 = User::create(['name' => 'User1', 'email' => 'u1_' . uniqid() . '@example.com', 'password' => Hash::make('secret123')]);
        $user2 = User::create(['name' => 'User2', 'email' => 'u2_' . uniqid() . '@example.com', 'password' => Hash::make('secret123')]);

        $product = Product::first();
        $size = Size::first();

        // Order 1 (Delivered)
        $order1 = Order::create([
            'order_number' => 'YSA-2026-222222',
            'user_id' => $user1->id,
            'customer_name' => $user1->name,
            'customer_phone' => '05321111111',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Adres 1',
            'subtotal' => 500,
            'total_amount' => 500,
            'status' => 'teslim_edildi',
            'payment_method' => 'kapida_odeme',
        ]);
        $item1 = OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'size_number' => $size->size_number,
            'price' => 500,
            'quantity' => 1,
            'total' => 500,
        ]);

        // Order 2 (User 2's Order)
        $order2 = Order::create([
            'order_number' => 'YSA-2026-333333',
            'user_id' => $user2->id,
            'customer_name' => $user2->name,
            'customer_phone' => '05322222222',
            'city' => 'Ankara',
            'district' => 'Çankaya',
            'address' => 'Adres 2',
            'subtotal' => 500,
            'total_amount' => 500,
            'status' => 'teslim_edildi',
            'payment_method' => 'kapida_odeme',
        ]);
        $item2 = OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'size_number' => $size->size_number,
            'price' => 500,
            'quantity' => 1,
            'total' => 500,
        ]);

        $this->actingAs($user1);

        // User 1 tries to attach item2 (from Order 2) to Order 1 -> must fail
        $respMismatched = $this->post(route('customer.returns.store'), [
            'order_id' => $order1->id,
            'order_item_id' => $item2->id,
            'type' => 'return',
            'reason' => 'Yanlış ürün gönderildi lütfen iade.',
        ]);
        $respMismatched->assertSessionHas('error');
        $this->assertDatabaseMissing('return_requests', ['order_item_id' => $item2->id]);

        // First valid return request succeeds
        $respValid = $this->post(route('customer.returns.store'), [
            'order_id' => $order1->id,
            'order_item_id' => $item1->id,
            'type' => 'return',
            'reason' => 'Numarası ayağıma dar geldi iade.',
        ]);
        $respValid->assertSessionHas('success');
        $this->assertDatabaseHas('return_requests', ['order_id' => $order1->id, 'order_item_id' => $item1->id]);

        // Duplicate return request on the same active item must be denied
        $respDuplicate = $this->post(route('customer.returns.store'), [
            'order_id' => $order1->id,
            'order_item_id' => $item1->id,
            'type' => 'return',
            'reason' => 'Tekrar iade talebi oluşturmayı deniyorum.',
        ]);
        $respDuplicate->assertSessionHas('error');
    }

    /**
     * 4 & 5 & 6. PayTR Payment Service: Signature Verification, Price Manipulation & Callback Idempotency.
     */
    public function test_payment_service_signature_amount_validation_and_idempotency(): void
    {
        config(['services.paytr.merchant_id' => '123456']);
        config(['services.paytr.merchant_key' => 'secret_key_abc']);
        config(['services.paytr.merchant_salt' => 'secret_salt_xyz']);

        $service = new PayTrPaymentService();

        $order = Order::create([
            'order_number' => 'YSA-2026-999888',
            'customer_name' => 'PayTR Test Customer',
            'customer_phone' => '05330001122',
            'customer_email' => 'customer@test.com',
            'city' => 'İstanbul',
            'district' => 'Şişli',
            'address' => 'Büyükdere Cad.',
            'subtotal' => 1500,
            'total_amount' => 1500.00,
            'status' => 'yeni',
            'payment_method' => 'paytr',
            'payment_status' => 'beklemede',
        ]);

        $merchantSalt = 'secret_salt_xyz';
        $merchantKey = 'secret_key_abc';

        // Case A: Forged Hash / Signature
        $forgedRequest = Request::create('/odeme/callback/paytr', 'POST', [
            'merchant_oid' => $order->order_number,
            'status' => 'success',
            'total_amount' => '150000',
            'hash' => 'invalid_forged_hash',
        ]);
        $resA = $service->handleCallback($forgedRequest);
        $this->assertFalse($resA['success']);
        $this->assertEquals('beklemede', $order->fresh()->payment_status);

        // Case B: Price Manipulation (Payment amount 10.00 TL instead of 1500.00 TL)
        $tamperedHashStr = $order->order_number . $merchantSalt . 'success' . '1000';
        $tamperedHash = base64_encode(hash_hmac('sha256', $tamperedHashStr, $merchantKey, true));

        $tamperedRequest = Request::create('/odeme/callback/paytr', 'POST', [
            'merchant_oid' => $order->order_number,
            'status' => 'success',
            'total_amount' => '1000', // 10 TL
            'hash' => $tamperedHash,
        ]);
        $resB = $service->handleCallback($tamperedRequest);
        $this->assertFalse($resB['success']);
        $this->assertStringContainsString('Ödeme tutarı sipariş tutarı ile uyuşmuyor', $resB['message']);
        $this->assertEquals('beklemede', $order->fresh()->payment_status);

        // Case C: Valid Payment Callback
        $validAmount = (int) round($order->total_amount * 100);
        $validHashStr = $order->order_number . $merchantSalt . 'success' . (string) $validAmount;
        $validHash = base64_encode(hash_hmac('sha256', $validHashStr, $merchantKey, true));

        $validRequest = Request::create('/odeme/callback/paytr', 'POST', [
            'merchant_oid' => $order->order_number,
            'status' => 'success',
            'total_amount' => (string) $validAmount,
            'hash' => $validHash,
        ]);
        $resC = $service->handleCallback($validRequest);
        $this->assertTrue($resC['success']);
        $this->assertEquals('odendi', $order->fresh()->payment_status);
        $this->assertEquals('hazirlaniyor', $order->fresh()->status);

        // Case D: Duplicate Callback (Idempotency)
        $resD = $service->handleCallback($validRequest);
        $this->assertTrue($resD['success']);
        $this->assertTrue($resD['idempotent'] ?? false);
        $this->assertEquals('odendi', $order->fresh()->payment_status);
    }

    /**
     * 7. Coupon Usage Limit Concurrency & Exhaustion.
     */
    public function test_coupon_usage_limit_exhaustion(): void
    {
        $coupon = Coupon::create([
            'code' => 'LIMITED1',
            'type' => 'fixed',
            'value' => 50,
            'usage_limit' => 1,
            'used_count' => 0,
            'minimum_order_amount' => 100,
            'is_active' => true,
        ]);

        $val1 = $coupon->validateForSubtotal(500);
        $this->assertTrue($val1['valid']);

        // Simulate first checkout consummation
        $coupon->increment('used_count');
        $this->assertEquals(1, $coupon->fresh()->used_count);

        // Second checkout attempts to validate exhausted coupon
        $val2 = $coupon->fresh()->validateForSubtotal(500);
        $this->assertFalse($val2['valid']);
        $this->assertStringContainsString('kullanım limiti dolmuştur', $val2['message']);
    }

    /**
     * 8. Stock Concurrency: Order cannot proceed when remaining stock is 0.
     */
    public function test_order_stock_locking_prevents_overselling(): void
    {
        $product = Product::first();
        $size = Size::first();

        $stockRecord = ProductSizeStock::updateOrCreate(
            ['product_id' => $product->id, 'size_id' => $size->id],
            ['stock' => 1]
        );

        $cartService = app(CartService::class);
        $orderService = app(OrderService::class);

        // Add 1 to cart
        $cartService->add($product->id, $size->id, 1);

        $orderPayload = [
            'customer_name' => 'Single Stock Buyer',
            'customer_phone' => '05330009988',
            'city' => 'İstanbul',
            'district' => 'Beşiktaş',
            'address' => 'Levent Mah.',
            'payment_method' => 'kapida_odeme',
        ];

        // 1st order succeeds
        $result1 = $orderService->createOrder($orderPayload);
        $this->assertTrue($result1['success']);
        $this->assertEquals(0, $stockRecord->fresh()->stock);

        // Simulate second concurrent user who already had the item in cart before stock became 0
        session()->put('shopping_cart', [
            $product->id . '_' . $size->id => [
                'product_id' => $product->id,
                'size_id' => $size->id,
                'quantity' => 1,
                'product_name' => $product->name,
                'size_number' => $size->size_number,
                'price' => $product->price,
            ]
        ]);

        $result2 = $orderService->createOrder($orderPayload);
        $this->assertFalse($result2['success']);
        $this->assertStringContainsString('yeterli stok bulunamadı', $result2['message']);
        $this->assertEquals(0, $stockRecord->fresh()->stock); // Stock never goes below 0
    }

    /**
     * 9. Duplicate Return Completion Idempotency: Restock happens only once.
     */
    public function test_duplicate_return_completion_does_not_double_restock(): void
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin_test_' . uniqid() . '@example.com', 'password' => Hash::make('secret123'), 'is_admin' => true]);

        $product = Product::first();
        $size = Size::first();

        $stockRecord = ProductSizeStock::updateOrCreate(
            ['product_id' => $product->id, 'size_id' => $size->id],
            ['stock' => 5]
        );

        $order = Order::create([
            'order_number' => 'YSA-2026-777777',
            'customer_name' => 'Return Tester',
            'customer_phone' => '05329990000',
            'city' => 'Bursa',
            'district' => 'Nilüfer',
            'address' => 'Özlüce',
            'subtotal' => 600,
            'total_amount' => 600,
            'status' => 'teslim_edildi',
            'payment_method' => 'kapida_odeme',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'size_number' => $size->size_number,
            'price' => 600,
            'quantity' => 1,
            'total' => 600,
        ]);

        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'type' => 'return',
            'reason' => 'İade talebi',
            'status' => 'bekliyor',
        ]);

        $this->actingAs($admin);

        // 1st completion -> Stock increases from 5 to 6
        $this->post(route('admin.returns.update-status', $returnRequest->id), ['status' => 'tamamlandi']);
        $this->assertEquals(6, $stockRecord->fresh()->stock);

        // 2nd completion call -> Must NOT increment stock again
        $this->post(route('admin.returns.update-status', $returnRequest->id), ['status' => 'tamamlandi']);
        $this->assertEquals(6, $stockRecord->fresh()->stock);
    }

    /**
     * 10. Stock Notification: Triggers only on 0 -> >0 and marks notified.
     */
    public function test_stock_notification_deduplication(): void
    {
        Mail::fake();

        $product = Product::first();
        $size = Size::first();

        $sub = StockNotification::create([
            'product_id' => $product->id,
            'size_id' => $size->id,
            'email' => 'subscriber@test.com',
            'is_notified' => false,
        ]);

        // Trigger notification
        $count1 = StockNotificationService::notifySubscribers($product, $size, 3);
        $this->assertEquals(1, $count1);
        $this->assertTrue($sub->fresh()->is_notified);
        $this->assertNotNull($sub->fresh()->notified_at);

        // Second restock (e.g. 3 -> 5) should not notify again
        $count2 = StockNotificationService::notifySubscribers($product, $size, 5);
        $this->assertEquals(0, $count2);

        Mail::assertQueued(StockAvailableNotificationMail::class, 1);
    }
}
