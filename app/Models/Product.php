<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name'];

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getCoverAttribute($value)
    {
        return url('/') .'/Backend/uploads/products/'.$value;
    }

    public function getOldCoverAttribute($value)
    {
        return url('/') .'/Backend/uploads/products/'.$value;
    }
}
