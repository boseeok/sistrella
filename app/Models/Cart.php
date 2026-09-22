<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'coupon_id', 'coupon_code'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->where('saved_for_later', false);
    }

    public function savedItems(): HasMany
    {
        return $this->hasMany(CartItem::class)->where('saved_for_later', true);
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
