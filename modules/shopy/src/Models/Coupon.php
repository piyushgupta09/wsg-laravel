<?php

namespace Fpaipl\Shopy\Models;

use App\Models\User;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Prody\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use Authx;

    protected $fillable = [
        'code', 
        'type', 
        'value',
        'max_value', 
        'min_value', 
        'max_usage', 
        'max_usage_per_user', 
        'valid_from', 
        'valid_to', 
        'active',
        'detail',
        'applicable',
        'products'
    ];

    const APPLICABLE = ['all', 'product', 'collection', 'category', 'brand', 'user', 'users'];

    public function getDiscount($total)
    {
        if ($this->type == 'percentage') {
            return $total * $this->value / 100;
        } else {
            return $this->value;
        }
    }

    public function getDiscountedTotal($total)
    {
        return $total - $this->getDiscount($total);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTableData($key)
    {
        switch ($key) {
            default: return $this->{$key};
        }
    }

    public function checkEligibility(User $user, Order $order)
    {
        $couponLimit = OrderCoupon::where('coupon_id', $this->id)->where('order_id', $order->id)->count() >= $this->max_usage;
        $userLimit = OrderCoupon::where('coupon_id', $this->id)->where('order_id', $order->id)->where('user_id', $user->id)->count() >= $this->max_usage_per_user;
        
        // if both limits are reached then return false
        if ($couponLimit && $userLimit) {
            return false;
        }

        return true;
    }

    // public function checkEligibilityForProduct(Product $product)
    // {
    //     //check if applicable to products or all
    //     if ($this->applicable == 'product' && $product) {
    //         if (!in_array($product->sid, $this->products)) {
    //             return false;
    //         }
    //     }

    //     // if type fixed return false
    //     if ($this->type == 'fixed') {
    //         return false;
    //     }

    //     return true;
    // }
}
