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
    protected $hidden = ['translations'];
    public $translatedAttributes = ['name'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function getCoverAttribute($value)
    {
        return url('/') .'/uploads/countries/'.$value;
    }
}