<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
    public function getCoverAttribute($value)
    {
        return url('/') .'/Backend/uploads/countries/'.$value;
    }
}
