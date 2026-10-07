<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        'shipping_company',
        'tracking_number',
        'tracking_url',
        'shipped_at',
        'delivered_at',
        'coupon_code',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class);
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

    public function getEffectiveTrackingUrlAttribute(): ?string
    {
        if (!empty($this->tracking_url)) {
            return $this->tracking_url;
        }

        if (empty($this->tracking_number)) {
            return null;
        }

        $company = mb_strtolower((string) $this->shipping_company, 'UTF-8');
        $code = urlencode(trim((string) $this->tracking_number));

        if (str_contains($company, 'yurtiçi') || str_contains($company, 'yurtici')) {
            return "https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code={$code}";
        }
        if (str_contains($company, 'aras')) {
            return "https://www.araskargo.com.tr/kargo-takip?kargo_takip_no={$code}";
        }
        if (str_contains($company, 'mng')) {
            return "https://www.mngkargo.com.tr/gonderitakip?trackingNumber={$code}";
        }
        if (str_contains($company, 'ptt')) {
            return "https://gonderitakip.ptt.gov.tr/Track/Verify?q={$code}";
        }
        if (str_contains($company, 'sürat') || str_contains($company, 'surat')) {
            return "https://www.suratkargo.com.tr/KargoTakip/?kargotakipno={$code}";
        }
        if (str_contains($company, 'hepsijet')) {
            return "https://hepsijet.com/gonderi-takibi/{$code}";
        }
        if (str_contains($company, 'trendyol')) {
            return "https://kargotakip.trendyol.com/?trackingNumber={$code}";
        }

        return null;
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
