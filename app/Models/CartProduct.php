<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProduct extends Pivot
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'is_unit',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
