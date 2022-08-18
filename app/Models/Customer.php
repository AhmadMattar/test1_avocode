<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id')->join('products', 'carts.product_id', 'products.id')
        ->select('carts.*', DB::raw('carts.quantity * products.price as price'));
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getCoverAttribute($value)
    {
        return $value ? url('/') .'/Backend/uploads/users/'.$value : null;
    }
}
