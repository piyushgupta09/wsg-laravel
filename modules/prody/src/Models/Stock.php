<?php

namespace Fpaipl\Prody\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'sku', // stock keeping unit
        'sid',
        'product_id',
        'product_option_id',
        'product_range_id',
        'product_option_sid',
        'product_range_sid',
        'product_slug',
        'product_code',
        'quantity',
        'active',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sid = $model->product_code . '-' . $model->product_option_id . '-' . $model->product_range_id;
            $model->sku = $model->product_slug . '_' . $model->product_option_sid . '_' . $model->product_range_sid;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
