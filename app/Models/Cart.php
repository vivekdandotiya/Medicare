<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->medicine->selling_price;
        });
    }

    public function getMrpSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->medicine->mrp;
        });
    }

    public function getSavingsAttribute()
    {
        return $this->mrp_subtotal - $this->subtotal;
    }
}
