<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'description', 'price', 'stock', 'category_id'];

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
}
