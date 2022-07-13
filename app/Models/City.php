<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory, Translatable;

    protected $guarded = [];
    protected $hidden = ['translations'];
    public $translatedAttributes = ['name'];
    
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
