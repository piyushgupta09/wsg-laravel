<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Prody\Models\ProductRange;
use Fpaipl\Prody\Models\ProductOption;
use Fpaipl\Shopy\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'quantity', 
        'cart_product_id', 
        'product_option_id', 
        'product_range_id'
    ];
  
    public function cartProduct(): BelongsTo
    {
        return $this->belongsTo(CartProduct::class);
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function productRange(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }
}
