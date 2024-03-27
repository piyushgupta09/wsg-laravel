<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Coupon;
use Fpaipl\Panel\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Fpaipl\Authy\Traits\HasBillingAddress;
use Fpaipl\Authy\Traits\HasShippingAddress;

class Checkout extends Model
{
    use
        Authx, 
        BelongsToUser,
        HasBillingAddress,
        HasShippingAddress;

    protected $fillable = [
        'user_id', 
        'billing_address_id', 
        'billing_shipping_same', 
        'shipping_address_id', 
        'pickup_address_id',
        'delivery_type', 
        'name', 'contact', 'secret', 'note', 'datetime',
        'coupon_id',
        'coupon_value',
        'pay_mode',
        'pay_amt',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
