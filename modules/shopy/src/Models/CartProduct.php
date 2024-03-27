<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Prody\Models\Product;
use Fpaipl\Shopy\Models\Cart;
use Fpaipl\Shopy\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartProduct extends Model
{
    protected $fillable = [
        'quantity',
        'order_type',
        'cart_id',
        'product_id',
        'draft',
        'taxtype', // intrastate, interstate and union-territory
    ];

    const TAXTYPE = [
        'intrastate', // CGST + SGST
        'interstate', // IGST
        'union-territory', // UTGST
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var User $user */
            $user = auth()->user();
            $model->taxtype = $user->address()?->getTaxType();
        });
    }
    
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }   
}
