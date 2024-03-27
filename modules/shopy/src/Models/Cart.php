<?php

namespace Fpaipl\Shopy\Models;

use App\Models\User;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Prody\Models\Product;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Cart extends Model
{
    use Authx, LogsActivity;

    protected $fillable = ['name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }

    public function getCouponValue(Coupon $coupon)
    {
        if ($coupon->applicable == 'products') {
            if (!$coupon->products || $coupon->products == [] || $coupon->products == null) {
                return 0;
            } else {
                $sumOfCartBeforeTax = $this->cartProducts->sum(function ($cartProduct) use ($coupon) {
                    if (in_array($cartProduct->product_id, $coupon->products)) {
                        return $this->calculateCartSumBeforeTax($cartProduct);
                    }
                });
            }
        } else {
            $sumOfCartBeforeTax = $this->cartProducts->sum(function ($cartProduct) {
                return $this->calculateCartSumBeforeTax($cartProduct);
            });
        }
        
        // Apply the coupon based on its type
        if ($coupon->type == 'percentage') {
            // Apply percentage discount to the sum of the cart
            $couponValue = $sumOfCartBeforeTax * ($coupon->value / 100);
            // Round the value to 2 decimal places
            $couponValue = round($couponValue, 2);
            // Ensure the discount does not exceed the maximum value
            return min($couponValue, $coupon->max_value);
        } else if ($coupon->type == 'fixed') {
            // For fixed discount, ensure it does not exceed the sum of the cart
            return min($coupon->value, $sumOfCartBeforeTax);
        }
    }

    private function calculateCartSumBeforeTax($cartProduct)
    {
        $totalPriceBeforeTax = 0;
    
        foreach ($cartProduct->cartItems as $item) {
            $inclusivePrice = $item->productRange->rate; // Price including tax
            $productTaxRate = $cartProduct->product->tax->gstrate;
    
            // Correctly calculating the price before tax
            $priceBeforeTax = $inclusivePrice / (1 + ($productTaxRate / 100));

            // round the price to 2 decimal places
            $priceBeforeTax = round($priceBeforeTax, 2);
            
            // Accumulate the total price before tax, considering the quantity of each item
            $totalPriceBeforeTax += $priceBeforeTax * $item->quantity;
        }
    
        return round($totalPriceBeforeTax, 2);
    }
    
    public function getTotal($payModeId)
    {
        // Find the matching pay mode based on the provided ID
        $payModes = config('settings.pay_modes');
        $payMode = collect($payModes)->firstWhere('id', $payModeId);
    
        $total = 0;
        foreach ($this->cartProducts as $cartProduct) {
            $total += $this->calculateCartSum($cartProduct);
        }
    
        // Check if payMode is found and has a 'multiple' value; otherwise, default to 1
        $multiple = $payMode['multiple'] ?? 1;
    
        // Apply the multiple to calculate the total based on the pay mode
        return $total * $multiple;
    }    

    private function calculateCartSum($cartProduct)
    {
        $totalPrice = 0;
    
        foreach ($cartProduct->cartItems as $item) {
            // Price including tax
            $inclusivePrice = $item->productRange->rate; 
            // Accumulate the total price before tax, considering the quantity of each item
            $totalPrice += $inclusivePrice * $item->quantity;
        }
    
        return round($totalPrice, 2);
    }
}
