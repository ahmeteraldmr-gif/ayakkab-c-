<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'type', // 'return', 'exchange'
        'reason',
        'requested_size',
        'status', // 'bekliyor', 'inceleniyor', 'onaylandi', 'reddedildi', 'tamamlandi'
        'admin_note',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'bekliyor' => 'Talep Alındı (Bekliyor)',
            'inceleniyor' => 'İnceleniyor',
            'onaylandi' => 'Onaylandı (Ürün Bekleniyor)',
            'reddedildi' => 'Reddedildi',
            'tamamlandi' => 'İşlem Tamamlandı',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'bekliyor' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'inceleniyor' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'onaylandi' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
            'reddedildi' => 'bg-rose-50 text-rose-700 border border-rose-200',
            'tamamlandi' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            default => 'bg-gray-50 text-gray-700 border border-gray-200',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'exchange' ? 'Beden Değişimi' : 'Ürün İadesi';
    }
}
