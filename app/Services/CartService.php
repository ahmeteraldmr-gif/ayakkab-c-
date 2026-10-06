<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Setting;
use App\Models\Size;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'shopping_cart';

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
     * Clear cart
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
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
     * Get shipping cost
     */
    public function getShippingCost(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0;
        }

        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 1500);
        $defaultShippingCost = (float) Setting::get('shipping_cost', 99);

        if ($subtotal >= $freeShippingThreshold) {
            return 0;
        }

        return $defaultShippingCost;
    }

    /**
     * Get total
     */
    public function getTotal(): float
    {
        return (float) ($this->getSubtotal() + $this->getShippingCost());
    }

    /**
     * Summary calculation
     */
    public function getSummary(): array
    {
        $subtotal = $this->getSubtotal();
        $shipping = $this->getShippingCost();
        $total = $this->getTotal();
        $freeThreshold = (float) Setting::get('free_shipping_threshold', 1500);
        $remainingForFreeShipping = max(0, $freeThreshold - $subtotal);

        return [
            'items' => array_values($this->getCart()),
            'count' => $this->getCount(),
            'subtotal' => $subtotal,
            'subtotal_formatted' => number_format($subtotal, 2, ',', '.') . ' ₺',
            'shipping' => $shipping,
            'shipping_formatted' => $shipping == 0 ? 'Ücretsiz' : number_format($shipping, 2, ',', '.') . ' ₺',
            'total' => $total,
            'total_formatted' => number_format($total, 2, ',', '.') . ' ₺',
            'free_shipping_threshold' => $freeThreshold,
            'free_shipping_remaining' => $remainingForFreeShipping,
            'free_shipping_percent' => $freeThreshold > 0 ? min(100, round(($subtotal / $freeThreshold) * 100)) : 100,
        ];
    }
}
