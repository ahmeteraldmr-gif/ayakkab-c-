<?php

namespace Tests\Feature;

use App\Mail\StockAvailableNotificationMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductSizeStock;
use App\Models\ReturnRequest;
use App\Models\Size;
use App\Models\StockNotification;
use App\Models\User;
use App\Services\StockNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PhaseTwoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure default sizes exist
        if (Size::count() === 0) {
            foreach ([40, 41, 42, 43, 44] as $idx => $sz) {
                Size::create(['size_number' => (string)$sz, 'sort_order' => $idx]);
            }
        }
    }

    /**
     * Test Customer Registration and Login
     */
    public function test_customer_can_register_and_login(): void
    {
        $regResponse = $this->post(route('register.submit'), [
            'name' => 'Ahmet Müşteri',
            'email' => 'ahmet_musteri_' . uniqid() . '@example.com',
            'phone' => '05321112233',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'kvkk_approval' => '1',
        ]);

        $regResponse->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * Test Address Book CRUD and IDOR security
     */
    public function test_customer_address_crud_and_security(): void
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        // User 1 creates address
        $this->actingAs($user1);
        $resp = $this->post(route('customer.addresses.store'), [
            'title' => 'Evim',
            'full_name' => 'User One',
            'phone' => '05320000001',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Bağdat Cad. No: 10',
            'is_default' => '1',
        ]);
        $resp->assertRedirect();

        $addr1 = CustomerAddress::where('user_id', $user1->id)->first();
        $this->assertNotNull($addr1);
        $this->assertTrue($addr1->is_default);

        // User 2 cannot delete User 1 address (IDOR protection)
        $this->actingAs($user2);
        $delResp = $this->delete(route('customer.addresses.destroy', $addr1->id));
        $delResp->assertStatus(403);
        $this->assertDatabaseHas('customer_addresses', ['id' => $addr1->id]);
    }

    /**
     * Test Product Review submission and verified purchase detection
     */
    public function test_product_review_and_verified_purchase_auto_detection(): void
    {
        $user = User::create([
            'name' => 'Verified Buyer',
            'email' => 'buyer_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $product = Product::create([
            'name' => 'Review Test Shoe',
            'slug' => 'review-test-shoe-' . uniqid(),
            'sku' => 'SKU-' . uniqid(),
            'price' => 1200,
            'gender' => 'unisex',
            'fit_type' => 'tam_kalip',
            'is_active' => true,
        ]);

        // Create a delivered order for this user containing this product
        $order = Order::create([
            'order_number' => 'YSA-' . date('Y') . '-' . mt_rand(100000, 999999),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => '05329998877',
            'customer_email' => $user->email,
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Test Mah. No: 1',
            'subtotal' => 1200,
            'total_amount' => 1200,
            'status' => 'teslim_edildi',
            'payment_method' => 'kapida_odeme',
            'payment_status' => 'odendi',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'size_number' => '42',
            'price' => 1200,
            'quantity' => 1,
            'total' => 1200,
        ]);

        // User submits review
        $this->actingAs($user);
        $revResp = $this->post(route('reviews.store', $product->id), [
            'customer_name' => 'Verified Buyer',
            'rating' => 5,
            'title' => 'Harika bir model',
            'comment' => 'Kalıbı tam oturdu ve çok rahat.',
        ]);

        $revResp->assertRedirect();

        $review = ProductReview::where('product_id', $product->id)->first();
        $this->assertNotNull($review);
        $this->assertTrue($review->is_verified_purchase);
        $this->assertFalse($review->is_approved); // Pending admin moderation

        // Admin approves review
        $admin = User::create([
            'name' => 'Admin Moderator',
            'email' => 'admin_mod_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin);
        $this->post(route('admin.reviews.approve', $review->id))->assertRedirect();
        $this->assertTrue($review->fresh()->is_approved);
    }

    /**
     * Test Return / Exchange Request creation and stock restoration on completion
     */
    public function test_return_request_creation_and_stock_restoration(): void
    {
        $user = User::create([
            'name' => 'Return Customer',
            'email' => 'return_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => false,
        ]);

        $product = Product::create([
            'name' => 'Restock Shoe',
            'slug' => 'restock-shoe-' . uniqid(),
            'sku' => 'SKU-' . uniqid(),
            'price' => 900,
            'gender' => 'erkek',
            'is_active' => true,
        ]);

        $size = Size::first();
        $stock = ProductSizeStock::create([
            'product_id' => $product->id,
            'size_id' => $size->id,
            'stock' => 5,
        ]);

        $order = Order::create([
            'order_number' => 'YSA-' . date('Y') . '-' . mt_rand(100000, 999999),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => '05321110000',
            'city' => 'Ankara',
            'district' => 'Çankaya',
            'address' => 'Tunalı Hilmi Cad.',
            'subtotal' => 900,
            'total_amount' => 900,
            'status' => 'teslim_edildi',
            'payment_method' => 'kapida_odeme',
            'payment_status' => 'odendi',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'size_number' => $size->size_number,
            'price' => 900,
            'quantity' => 1,
            'total' => 900,
        ]);

        // Customer creates return request
        $this->actingAs($user);
        $retResp = $this->post(route('customer.returns.store'), [
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'type' => 'return',
            'reason' => 'Ayağıma uymadı, iade etmek istiyorum.',
        ]);
        $retResp->assertRedirect();

        $returnRequest = ReturnRequest::where('order_id', $order->id)->first();
        $this->assertNotNull($returnRequest);
        $this->assertEquals('bekliyor', $returnRequest->status);

        // Admin completes return request -> stock should increase by 1
        $admin = User::create([
            'name' => 'Admin Stock Manager',
            'email' => 'admin_stock_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin);
        $this->post(route('admin.returns.update-status', $returnRequest->id), [
            'status' => 'tamamlandi',
            'admin_note' => 'İade teslim alındı, stok geri yüklendi.',
        ]);

        $this->assertEquals('tamamlandi', $returnRequest->fresh()->status);
        $this->assertEquals(6, $stock->fresh()->stock); // 5 + 1
    }

    /**
     * Test Stock Notification Automation Mail Dispatch
     */
    public function test_stock_notification_service_dispatches_mail_on_restock(): void
    {
        Mail::fake();

        $product = Product::create([
            'name' => 'Notify Alert Shoe',
            'slug' => 'notify-alert-shoe-' . uniqid(),
            'sku' => 'SKU-' . uniqid(),
            'price' => 1500,
            'gender' => 'erkek',
            'is_active' => true,
        ]);

        $size = Size::first();

        $alert = StockNotification::create([
            'product_id' => $product->id,
            'size_id' => $size->id,
            'email' => 'buyer_wait_' . uniqid() . '@example.com',
            'is_notified' => false,
        ]);

        // Trigger notification
        StockNotificationService::notifySubscribers($product, $size, 5);

        Mail::assertQueued(StockAvailableNotificationMail::class, function ($mail) use ($alert) {
            return $mail->hasTo($alert->email);
        });

        $this->assertTrue($alert->fresh()->is_notified);
        $this->assertNotNull($alert->fresh()->notified_at);
    }

    /**
     * Test Checkout Requires Mandatory Legal Agreements
     */
    public function test_checkout_validation_requires_legal_agreements(): void
    {
        $product = Product::create([
            'name' => 'Agreement Test Shoe',
            'slug' => 'agreement-test-shoe-' . uniqid(),
            'sku' => 'SKU-' . uniqid(),
            'price' => 500,
            'gender' => 'kadin',
            'is_active' => true,
        ]);
        $size = Size::first();
        ProductSizeStock::create([
            'product_id' => $product->id,
            'size_id' => $size->id,
            'stock' => 10,
        ]);

        // Add to cart
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);

        // Try checkout without checkboxes
        $resp = $this->post(route('checkout.process'), [
            'customer_name' => 'Test Müşteri',
            'customer_phone' => '05320001122',
            'city' => 'İzmir',
            'district' => 'Konak',
            'address' => 'Alsancak Mah. No: 5',
            'payment_method' => 'kapida_odeme',
            // Missing pre_info_approval & distance_selling_approval
        ]);

        $resp->assertSessionHasErrors(['pre_info_approval', 'distance_selling_approval']);

        // Now checkout WITH checkboxes
        $okResp = $this->post(route('checkout.process'), [
            'customer_name' => 'Test Müşteri',
            'customer_phone' => '05320001122',
            'city' => 'İzmir',
            'district' => 'Konak',
            'address' => 'Alsancak Mah. No: 5',
            'payment_method' => 'kapida_odeme',
            'pre_info_approval' => '1',
            'distance_selling_approval' => '1',
        ]);

        $okResp->assertRedirect();
        $this->assertDatabaseHas('orders', ['customer_name' => 'Test Müşteri']);
    }

    /**
     * Test Admin Reports & CSV Export
     */
    public function test_admin_reports_and_csv_export(): void
    {
        $admin = User::create([
            'name' => 'Admin Reporter',
            'email' => 'admin_rep_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $viewResp = $this->get(route('admin.reports.index'));
        $viewResp->assertStatus(200);
        $viewResp->assertSee('Satış ve Gelir Raporları');

        $csvResp = $this->get(route('admin.reports.export-csv'));
        $csvResp->assertStatus(200);
        $csvResp->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
