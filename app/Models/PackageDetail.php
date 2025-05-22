<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDetail extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'product_id'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
