<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Setting;
use App\Models\Size;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'shopping_cart';
    protected string $couponKey = 'applied_coupon_code';

    /**
     * Get all cart items
     */
    public function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }

    /**
     * Add product to cart with size
     */
    public function add(int $productId, int $sizeId, int $quantity = 1): array
    {
        $product = Product::with(['images', 'brand', 'category'])->findOrFail($productId);
        $size = Size::findOrFail($sizeId);

        // Check stock
        $stockRecord = ProductSizeStock::where('product_id', $productId)
                                       ->where('size_id', $sizeId)
                                       ->first();

        $availableStock = $stockRecord ? $stockRecord->stock : 0;

        if ($availableStock <= 0) {
            return [
                'success' => false,
                'message' => 'Seçilen numara maalesef stokta tükenmiştir.',
            ];
        }

        $cart = $this->getCart();
        $cartKey = $productId . '_' . $sizeId;

        $currentQty = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $availableStock) {
            return [
                'success' => false,
                'message' => "Stokta sadece {$availableStock} adet ürün bulunmaktadır.",
            ];
        }

        $cart[$cartKey] = [
            'key' => $cartKey,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'product_sku' => $product->sku,
            'product_image' => $product->primary_image_url,
            'brand_name' => $product->brand?->name ?? 'YSA',
            'size_id' => $size->id,
            'size_number' => $size->size_number,
            'price' => (float) $product->effective_price,
            'original_price' => (float) $product->price,
            'quantity' => $newQty,
            'max_stock' => $availableStock,
            'total' => (float) ($product->effective_price * $newQty),
        ];

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => 'Ürün başarıyla sepete eklendi.',
            'cart_count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
            'cart' => $cart,
        ];
    }

    /**
     * Update quantity
     */
    public function update(string $cartKey, int $quantity): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$cartKey])) {
            return ['success' => false, 'message' => 'Ürün sepette bulunamadı.'];
        }

        if ($quantity <= 0) {
            return $this->remove($cartKey);
        }

        $item = $cart[$cartKey];
        $stockRecord = ProductSizeStock::where('product_id', $item['product_id'])
                                       ->where('size_id', $item['size_id'])
                                       ->first();
        $availableStock = $stockRecord ? $stockRecord->stock : 0;

        if ($quantity > $availableStock) {
            return [
                'success' => false,
                'message' => "Stokta en fazla {$availableStock} adet mevcuttur.",
            ];
        }

        $cart[$cartKey]['quantity'] = $quantity;
        $cart[$cartKey]['total'] = (float) ($cart[$cartKey]['price'] * $quantity);
        $cart[$cartKey]['max_stock'] = $availableStock;

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => 'Sepet güncellendi.',
            'cart_count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
            'total' => $this->getTotal(),
            'shipping' => $this->getShippingCost(),
            'item_total' => $cart[$cartKey]['total'],
        ];
    }

    /**
     * Remove item
     */
    public function remove(string $cartKey): array
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put($this->sessionKey, $cart);
        }

        return [
            'success' => true,
            'message' => 'Ürün sepetten çıkarıldı.',
            'cart_count' => $this->getCount(),
            'subtotal' => $this->getSubtotal(),
            'total' => $this->getTotal(),
            'shipping' => $this->getShippingCost(),
        ];
    }

    /**
     * Clear cart and coupon
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
        Session::forget($this->couponKey);
    }

    /**
     * Get item count
     */
    public function getCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get subtotal
     */
    public function getSubtotal(): float
    {
        $cart = $this->getCart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        return (float) $subtotal;
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(string $code): array
    {
        $code = trim(strtoupper($code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'Geçersiz indirim kuponu kodu girdiniz.',
            ];
        }

        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return [
                'success' => false,
                'message' => 'Sepetinizde ürün bulunmamaktadır.',
            ];
        }

        $validation = $coupon->validateForSubtotal($subtotal);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
            ];
        }

        Session::put($this->couponKey, $coupon->code);

        return [
            'success' => true,
            'message' => $validation['message'],
            'discount' => $validation['discount'],
            'discount_formatted' => number_format($validation['discount'], 2, ',', '.') . ' ₺',
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(): array
    {
        Session::forget($this->couponKey);

        return [
            'success' => true,
            'message' => 'Kupon kodu kaldırıldı.',
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Get current valid Coupon model
     */
    public function getValidCoupon(): ?Coupon
    {
        $code = Session::get($this->couponKey);
        if (empty($code)) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            Session::forget($this->couponKey);
            return null;
        }

        $validation = $coupon->validateForSubtotal($this->getSubtotal());
        if (!$validation['valid']) {
            Session::forget($this->couponKey);
            return null;
        }

        return $coupon;
    }

    /**
     * Calculate active discount amount
     */
    public function getDiscountAmount(): float
    {
        $coupon = $this->getValidCoupon();
        if (!$coupon) {
            return 0.0;
        }

        return (float) $coupon->calculateDiscount($this->getSubtotal());
    }

    /**
     * Get shipping cost
     */
    public function getShippingCost(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.0;
        }

        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 1500);
        $defaultShippingCost = (float) Setting::get('shipping_cost', 99);

        if ($subtotal >= $freeShippingThreshold) {
            return 0.0;
        }

        return $defaultShippingCost;
    }

    /**
     * Get grand total
     */
    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.0;
        }

        $discount = $this->getDiscountAmount();
        $shipping = $this->getShippingCost();

        $total = max(0.0, ($subtotal - $discount)) + $shipping;
        return (float) round($total, 2);
    }

    /**
     * Summary calculation
     */
    public function getSummary(): array
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscountAmount();
        $shipping = $this->getShippingCost();
        $total = $this->getTotal();
        $coupon = $this->getValidCoupon();

        $freeThreshold = (float) Setting::get('free_shipping_threshold', 1500);
        $remainingForFreeShipping = max(0.0, $freeThreshold - $subtotal);

        return [
            'items' => array_values($this->getCart()),
            'count' => $this->getCount(),
            'subtotal' => $subtotal,
            'subtotal_formatted' => number_format($subtotal, 2, ',', '.') . ' ₺',
            'discount' => $discount,
            'discount_formatted' => number_format($discount, 2, ',', '.') . ' ₺',
            'has_coupon' => $coupon !== null,
            'coupon_code' => $coupon?->code,
            'coupon_type' => $coupon?->type,
            'coupon_value' => $coupon?->value,
            'shipping' => $shipping,
            'shipping_formatted' => $shipping == 0 ? 'Ücretsiz' : number_format($shipping, 2, ',', '.') . ' ₺',
            'total' => $total,
            'total_formatted' => number_format($total, 2, ',', '.') . ' ₺',
            'free_shipping_threshold' => $freeThreshold,
            'free_shipping_remaining' => $remainingForFreeShipping,
            'free_shipping_remaining_formatted' => number_format($remainingForFreeShipping, 2, ',', '.') . ' ₺',
            'free_shipping_percent' => $freeThreshold > 0 ? min(100, round(($subtotal / $freeThreshold) * 100)) : 100,
            'is_free_shipping' => ($shipping == 0 && $subtotal > 0),
        ];
    }
}
