<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPackageItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'package_id', 'quantity'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function orderPackageVariantItems()
    {
        return $this->hasMany(OrderPackageVariantItem::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
