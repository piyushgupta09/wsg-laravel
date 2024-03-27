<?php

namespace Fpaipl\Shopy\Models;

use App\Models\User;
use Fpaipl\Shopy\Models\Coupon;
use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'coupon_id',
        'value',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
