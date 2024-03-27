<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Prody\Models\ProductRange;
use Fpaipl\Prody\Models\ProductOption;
use Fpaipl\Shopy\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'mrp',
        'rate',
        'price',
        'discount',
        'quantity',
        'amount',
        'tax',
        'total',
        'order_product_id',
        'product_option_id',
        'product_range_id',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function productRange()
    {
        return $this->belongsTo(ProductRange::class);
    }
}
