<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'access_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city',
        'district',
        'address',
        'order_notes',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->access_token)) {
                $order->access_token = \Illuminate\Support\Str::random(40);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'yeni' => 'Yeni Sipariş',
            'hazirlaniyor' => 'Hazırlanıyor',
            'kargoda' => 'Kargoya Verildi',
            'tamamlandi' => 'Tamamlandı',
            'iptal' => 'İptal Edildi',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'yeni' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'hazirlaniyor' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'kargoda' => 'bg-purple-50 text-purple-700 border border-purple-200',
            'tamamlandi' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'iptal' => 'bg-red-50 text-red-700 border border-red-200',
            default => 'bg-gray-50 text-gray-700 border border-gray-200',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format((float) $this->total_amount, 2, ',', '.') . ' ₺';
    }

    /**
     * Get valid next statuses from current status.
     */
    public function allowedNextStatuses(): array
    {
        $transitions = [
            'yeni' => ['yeni', 'hazirlaniyor', 'iptal'],
            'hazirlaniyor' => ['hazirlaniyor', 'kargoda', 'iptal'],
            'kargoda' => ['kargoda', 'tamamlandi', 'iptal'],
            'tamamlandi' => ['tamamlandi'],
            'iptal' => ['iptal'],
        ];

        return $transitions[$this->status] ?? [$this->status];
    }

    /**
     * Check whether transition to new status is allowed.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedNextStatuses(), true);
    }
}
