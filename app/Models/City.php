<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory, Translatable;

    protected $guarded = [];
    protected $hidden = ['translations', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
