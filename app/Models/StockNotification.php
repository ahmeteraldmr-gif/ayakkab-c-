<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'email',
        'is_notified',
        'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_notified' => 'boolean',
            'notified_at' => 'datetime',
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
}
