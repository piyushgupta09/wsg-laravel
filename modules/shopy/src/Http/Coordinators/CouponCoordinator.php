<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Coupon;
use Fpaipl\Shopy\Models\OrderCoupon;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Shopy\Http\Resources\CouponResource;
use Fpaipl\Shopy\Http\Resources\CheckoutResource;

class CouponCoordinator extends Coordinator
{
    
    public function index()
    {
        $availableCoupons = Coupon::where('active', true)->where('valid_to', '>=', now())->get();
        $usedCoupons = OrderCoupon::where('user_id', auth()->id())->get()->map(function ($orderCoupon) {
            return $orderCoupon->coupon;
        });
        $expiredCoupons = Coupon::where('valid_to', '<', now())->get();

        return ApiResponse::success([
            'available' => CouponResource::collection($availableCoupons),
            'used' => CouponResource::collection($usedCoupons->unique()),
            'expired' => CouponResource::collection($expiredCoupons),
        ]);
    }

    public function couponApply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $checkout = $user->checkout;

        $coupon = Coupon::where('code', $request->code)->first();
    
        if ($coupon->active && $coupon->valid_to > now()) {
            $checkout->coupon_id = $coupon->id;
            $checkout->coupon_value = $checkout->user->cart->getCouponValue($coupon);
            $checkout->save();
            return ApiResponse::success([], 'Coupon applied successfully');
        } else {
            return ApiResponse::error('Coupon is not valid', 400);
        }
    }

    public function couponRemove(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $checkout = $user->checkout;

        $checkout->coupon_id = null;
        $checkout->coupon_value = 0;
        $checkout->save();

        return ApiResponse::success([], 'Coupon removed successfully');
    }
}
