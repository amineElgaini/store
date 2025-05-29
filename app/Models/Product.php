<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'price',
        'is_active',
        'category_id'
    ];

    /**
     * Category of the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Variants (size + color + stock).
     */
    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Images for each color of the product.
     */
    public function productColorImages(): HasMany
    {
        return $this->hasMany(ProductColorImage::class);
    }

    /**
     * Package details this product is part of.
     */
    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    /**
     * Order product items referencing this product.
     */
    public function orderProductItems(): HasMany
    {
        return $this->hasMany(OrderProductItem::class);
    }

    /**
     * Total stock across all variants.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->productVariants->sum('stock');
    }
}
