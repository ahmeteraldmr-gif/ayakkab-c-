<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'discount_price',
        'gender',
        'color',
        'color_code',
        'is_active',
        'is_featured',
        'is_new',
        'view_count',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'view_count' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'YSA-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function sizeStocks(): HasMany
    {
        return $this->hasMany(ProductSizeStock::class);
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size_stocks')
                    ->withPivot('stock')
                    ->withTimestamps()
                    ->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewArrivals(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    // Accessors & Helper Methods
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price 
            ? $this->discount_price 
            : $this->price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->has_discount && $this->price > 0) {
            return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->where('is_primary', true)->first() ?? $this->images->first();
        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http://') || str_starts_with($primary->image_path, 'https://')) {
                return $primary->image_path;
            }
            return asset('storage/' . $primary->image_path);
        }
        return asset('images/placeholder-shoe.svg');
    }

    public function getAllImagesUrlsAttribute(): array
    {
        if ($this->images->count() > 0) {
            return $this->images->map(function ($img) {
                if (str_starts_with($img->image_path, 'http://') || str_starts_with($img->image_path, 'https://')) {
                    return $img->image_path;
                }
                return asset('storage/' . $img->image_path);
            })->toArray();
        }
        return [asset('images/placeholder-shoe.svg')];
    }

    public function getTotalStockAttribute(): int
    {
        return (int) ($this->relationLoaded('sizeStocks') 
            ? $this->sizeStocks->sum('stock') 
            : $this->sizeStocks()->sum('stock'));
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price, 2, ',', '.') . ' ₺';
    }

    public function getFormattedEffectivePriceAttribute(): string
    {
        return number_format((float) $this->effective_price, 2, ',', '.') . ' ₺';
    }

    public function getFormattedDiscountPriceAttribute(): string
    {
        return $this->discount_price ? number_format((float) $this->discount_price, 2, ',', '.') . ' ₺' : '';
    }
}
