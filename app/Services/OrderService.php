<?php

namespace App\Services;

use App\Mail\OrderCreatedAdminMail;
use App\Mail\OrderCreatedCustomerMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Setting;
use App\Models\Size;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Create order with strict DB price calculation, stock locking, coupon and stock movements
     */
    public function createOrder(array $validatedData): array
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'Sepetinizde ürün bulunmamaktadır.',
                'order' => null,
            ];
        }

        try {
            return DB::transaction(function () use ($validatedData, $cart) {
                // 1. Fetch fresh DB products & verify active status
                $productIds = array_unique(array_column($cart, 'product_id'));
                $products = Product::whereIn('id', $productIds)
                                   ->where('is_active', true)
                                   ->with(['images', 'brand'])
                                   ->get()
                                   ->keyBy('id');

                $orderItemsToCreate = [];
                $subtotal = 0.0;

                foreach ($cart as $item) {
                    $productId = (int) $item['product_id'];
                    $sizeId = (int) $item['size_id'];
                    $quantity = (int) $item['quantity'];

                    if ($quantity <= 0) {
                        continue;
                    }

                    // Check product exists and is active in database
                    $product = $products->get($productId);
                    if (!$product) {
                        throw new \Exception("Sepetinizdeki '{$item['product_name']}' isimli ürün artık aktif satışta değildir.");
                    }

                    // Check size exists
                    $size = Size::find($sizeId);
                    if (!$size) {
                        throw new \Exception("Seçilen ayakkabı numarası bilgisi geçersizdir.");
                    }

                    // Lock and verify stock in DB
                    $stockRecord = ProductSizeStock::where('product_id', $productId)
                                                   ->where('size_id', $sizeId)
                                                   ->lockForUpdate()
                                                   ->first();

                    if (!$stockRecord || $stockRecord->stock < $quantity) {
                        $available = $stockRecord ? $stockRecord->stock : 0;
                        throw new \Exception("'{$product->name}' ({$size->size_number} numara) için yeterli stok bulunamadı. Kalan stok: {$available}");
                    }

                    // RE-CALCULATE strictly from DB effective price (Ignore any session/frontend manipulated price)
                    $unitPrice = (float) $product->effective_price;
                    $itemTotal = (float) ($unitPrice * $quantity);
                    $subtotal += $itemTotal;

                    $orderItemsToCreate[] = [
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'product_image' => $product->primary_image_url,
                        'size_number' => $size->size_number,
                        'price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $itemTotal,
                        'stock_record' => $stockRecord,
                    ];
                }

                if (empty($orderItemsToCreate)) {
                    throw new \Exception('Sipariş oluşturulacak geçerli ürün bulunamadı.');
                }

                // 2. Validate Coupon and Calculate Discount (strictly from DB)
                $discountAmount = 0.0;
                $couponCode = null;
                $appliedCoupon = $this->cartService->getValidCoupon();

                if ($appliedCoupon) {
                    $couponRecord = Coupon::where('id', $appliedCoupon->id)->lockForUpdate()->first();
                    if ($couponRecord) {
                        $couponVal = $couponRecord->validateForSubtotal($subtotal);
                        if ($couponVal['valid']) {
                            $discountAmount = (float) $couponVal['discount'];
                            $couponCode = $couponRecord->code;
                            $couponRecord->increment('used_count');
                        }
                    }
                }

                // 3. Calculate shipping cost from DB settings
                $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 1500);
                $defaultShippingCost = (float) Setting::get('shipping_cost', 99);
                $shippingCost = ($subtotal >= $freeShippingThreshold) ? 0.0 : $defaultShippingCost;
                
                $totalAmount = max(0.0, ($subtotal - $discountAmount)) + $shippingCost;

                // 4. Generate Unique Order Number
                $year = date('Y');
                do {
                    $randomSeq = str_pad((string) mt_rand(100001, 999999), 6, '0', STR_PAD_LEFT);
                    $orderNumber = "YSA-{$year}-{$randomSeq}";
                } while (Order::where('order_number', $orderNumber)->exists());

                $accessToken = Str::random(40);

                // 5. Create Order
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'access_token' => $accessToken,
                    'customer_name' => $validatedData['customer_name'],
                    'customer_phone' => $validatedData['customer_phone'],
                    'customer_email' => $validatedData['customer_email'] ?? null,
                    'city' => $validatedData['city'],
                    'district' => $validatedData['district'],
                    'address' => $validatedData['address'],
                    'order_notes' => $validatedData['order_notes'] ?? null,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'discount_amount' => $discountAmount,
                    'coupon_code' => $couponCode,
                    'total_amount' => $totalAmount,
                    'status' => 'yeni',
                    'payment_method' => $validatedData['payment_method'],
                    'payment_status' => $validatedData['payment_method'] === 'kapida_odeme' ? 'kapida_odenecek' : 'beklemede',
                ]);

                // 6. Create Order Items, Deduct Stock & Record Stock Movements
                foreach ($orderItemsToCreate as $itemData) {
                    $stockRecord = $itemData['stock_record'];
                    $sizeId = $itemData['size_id'];
                    unset($itemData['stock_record'], $itemData['size_id']);

                    $itemData['order_id'] = $order->id;
                    OrderItem::create($itemData);

                    $qtyBefore = (int) $stockRecord->stock;
                    $qtyChange = -(int) $itemData['quantity'];
                    $qtyAfter = max(0, $qtyBefore + $qtyChange);

                    // Safely decrement locked stock
                    $stockRecord->decrement('stock', $itemData['quantity']);

                    // Log Stock Movement
                    StockMovement::create([
                        'product_id' => $itemData['product_id'],
                        'size_id' => $sizeId,
                        'user_id' => auth()->id(),
                        'type' => 'order',
                        'quantity_before' => $qtyBefore,
                        'quantity_change' => $qtyChange,
                        'quantity_after' => $qtyAfter,
                        'reason' => "Sipariş: {$order->order_number}",
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                    ]);
                }

                // 7. Clear shopping cart
                $this->cartService->clear();

                // 8. Store security tokens in session
                session()->put('authorized_order_' . $order->order_number, $accessToken);
                session()->put('authorized_order_id_' . $order->id, true);
                session()->flash('last_order_id', $order->id);
                session()->flash('last_order_number', $order->order_number);

                // 9. Send Email Notifications (Non-blocking & Error-safe)
                try {
                    if (!empty($order->customer_email)) {
                        Mail::to($order->customer_email)->send(new OrderCreatedCustomerMail($order));
                    }

                    $adminEmail = Setting::get('site_email', config('mail.from.address'));
                    if (!empty($adminEmail)) {
                        Mail::to($adminEmail)->send(new OrderCreatedAdminMail($order));
                    }
                } catch (\Throwable $mailEx) {
                    Log::warning("Order email notification failed: " . $mailEx->getMessage(), [
                        'order_id' => $order->id,
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Siparişiniz başarıyla oluşturuldu.',
                    'order' => $order,
                ];
            });
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order' => null,
            ];
        }
    }
}
