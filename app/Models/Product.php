<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'description', 'price'/* , 'stock' */, 'is_active', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    public function orderProductItems(): HasMany
    {
        return $this->hasMany(OrderProductItem::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function colorImages()
    {
        return $this->hasMany(ProductColorImage::class);
    }

        // Helper to get unique colors related to product variants
        public function colorsUnique()
        {
            return $this->productVariants()
                ->with('color')
                ->get()
                ->pluck('color')
                ->unique('id')
                ->values();
        }
}
