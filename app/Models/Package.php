<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    public function orderPackageItems(): HasMany
    {
        return $this->hasMany(OrderPackageItem::class);
    }
}
