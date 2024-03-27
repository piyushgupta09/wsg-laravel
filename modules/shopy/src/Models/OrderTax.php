<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Prody\Models\Tax;
use Fpaipl\Shopy\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class OrderTax extends Model
{
    protected $fillable = [
        'order_product_id',
        'tax_id',
        'igst',
        'cgst',
        'sgst',
        'hsncode',
        'gstrate',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
