<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Shopy\Models\OrderTax;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderProduct extends Model
{
    use Authx, LogsActivity;

    protected $fillable = [
        'skus', 
        'quantity', 
        'amount', 
        'tax', 
        'total', 
        'order_id', 
        'product_id', 
        'suborder_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderTax()
    {
        return $this->hasOne(OrderTax::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }
}