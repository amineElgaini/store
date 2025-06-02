<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status', 'shipping_info'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderProductItems(): HasMany
    {
        return $this->hasMany(OrderProductItem::class);
    }

    public function orderPackageItems(): HasMany
    {
        return $this->hasMany(OrderPackageItem::class);
    }
}
