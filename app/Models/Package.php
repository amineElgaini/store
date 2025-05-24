<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'is_active'];

    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    public function orderPackageItems(): HasMany
    {
        return $this->hasMany(OrderPackageItem::class);
    }
}
