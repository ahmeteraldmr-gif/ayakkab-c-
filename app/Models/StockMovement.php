<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'user_id',
        'type', // 'manual_update', 'order', 'order_cancel', 'return', 'adjustment'
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reason',
        'reference_type',
        'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_before' => 'integer',
            'quantity_change' => 'integer',
            'quantity_after' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'order' => 'Sipariş Düşümü',
            'order_cancel' => 'Sipariş İptali / İade',
            'manual_update' => 'Manuel Düzenleme',
            'adjustment' => 'Stok Sayımı / Düzeltme',
            'return' => 'Müşteri İadesi',
            default => ucfirst($this->type),
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'order' => 'bg-rose-50 text-rose-700 border border-rose-200',
            'order_cancel', 'return' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'manual_update' => 'bg-blue-50 text-blue-700 border border-blue-200',
            default => 'bg-gray-50 text-gray-700 border border-gray-200',
        };
    }
}
