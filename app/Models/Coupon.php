<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type', // 'percentage', 'fixed'
        'value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if coupon is valid for a given subtotal.
     */
    public function isValid(float $subtotal = 0): bool
    {
        return $this->validateForSubtotal($subtotal)['valid'];
    }

    /**
     * Check if coupon is currently valid for a given subtotal.
     */
    public function validateForSubtotal(float $subtotal): array
    {
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'Bu kupon kodu şu anda aktif değildir.',
                'discount' => 0.0,
            ];
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return [
                'valid' => false,
                'message' => 'Bu kupon henüz kullanıma açılmamıştır.',
                'discount' => 0.0,
            ];
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return [
                'valid' => false,
                'message' => 'Bu kupon kodunun kullanım süresi dolmuştur.',
                'discount' => 0.0,
            ];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Bu kuponun toplam kullanım limiti dolmuştur.',
                'discount' => 0.0,
            ];
        }

        if ($this->minimum_order_amount !== null && $subtotal < (float) $this->minimum_order_amount) {
            $minFormatted = number_format((float) $this->minimum_order_amount, 2, ',', '.') . ' ₺';
            return [
                'valid' => false,
                'message' => "Bu kuponu kullanabilmek için sepet tutarınızın en az {$minFormatted} olması gerekmektedir.",
                'discount' => 0.0,
            ];
        }

        $discount = $this->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return [
                'valid' => false,
                'message' => 'Bu kupon sepetinize indirim uygulayamadı.',
                'discount' => 0.0,
            ];
        }

        return [
            'valid' => true,
            'message' => 'Kupon başarıyla uygulandı.',
            'discount' => $discount,
        ];
    }

    /**
     * Calculate discount strictly based on rules.
     */
    public function calculateDiscount(float $subtotal): float
    {
        $discount = 0.0;

        if ($this->type === 'percentage') {
            $discount = ($subtotal * (float) $this->value) / 100.0;
        } else {
            // Fixed amount
            $discount = (float) $this->value;
        }

        // Apply maximum discount ceiling if configured
        if ($this->maximum_discount_amount !== null && $discount > (float) $this->maximum_discount_amount) {
            $discount = (float) $this->maximum_discount_amount;
        }

        // Discount cannot exceed subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return round($discount, 2);
    }
}
