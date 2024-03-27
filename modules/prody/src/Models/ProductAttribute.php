<?php

namespace Fpaipl\Prody\Models;

use Fpaipl\Prody\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
