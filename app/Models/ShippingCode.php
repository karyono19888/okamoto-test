<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCode extends Model
{
    protected $fillable = [
        'code',
    ];

    public function containers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Container::class);
    }
}
