<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderPackageVariantItem extends Model
{
    use HasFactory;

    public function orderPackageItem()
    {
        return $this->belongsTo(OrderPackageItem::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
