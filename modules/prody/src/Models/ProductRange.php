<?php

namespace Fpaipl\Prody\Models;

use Fpaipl\Prody\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRange extends Model
{
    protected $guarded = [];

    const SHORT = [
        'free-size' => 'F', // Free Size
        'xsmall' => 'XS',
        'small' => 'S',
        'medium' => 'M',
        'large' => 'L',
        'xlarge' => 'XL',
        'xxlarge' => '2XL',
        'xxxlarge' => '3XL',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
