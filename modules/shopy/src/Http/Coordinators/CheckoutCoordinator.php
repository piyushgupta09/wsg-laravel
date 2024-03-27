<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Checkout;
use Illuminate\Support\Facades\Log;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Shopy\Http\Resources\CheckoutResource;
use Fpaipl\Shopy\Http\Requests\CheckoutBillingRequest;
use Fpaipl\Shopy\Http\Requests\CheckoutDeliveryRequest;

class CheckoutCoordinator extends Coordinator
{
    public function index()
    {
        /** @var User $user */
        $user = auth()?->user();
        $checkout = Checkout::where('user_id', $user->id)->firstOrFail();
        return ApiResponse::success(CheckoutResource::make($checkout), 'Checkout index.');
    }

    public function start()
    {
        /** @var User $user */
        $user = auth()?->user();

        if($user->cart->cartProducts->isEmpty()){
            return ApiResponse::success(null, 'Your cart is empty.');
        } 

        $checkout = Checkout::where('user_id', $user->id)->firstOrFail();
        return ApiResponse::success(CheckoutResource::make($checkout), 'Checkout started.');
    }
   
    public function billing(CheckoutBillingRequest $request)
    {
        /** @var User $user */
        $user = auth()?->user();

        if($user->cart->cartProducts->isEmpty()){
            return ApiResponse::success(null, 'Your cart is empty.');
        } 

        $checkout = Checkout::where('user_id', $user->id)->firstOrFail();
        $checkout->billing_address_id = $request->billing;
        $checkout->billing_shipping_same = $request->same;
        $checkout->shipping_address_id = $request->same ? $request->billing : $request->shipping;
        $checkout->save();

        return ApiResponse::success(CheckoutResource::make($checkout), 'Checkout billing ok.');
    }

    public function delivery(CheckoutDeliveryRequest $request)
    {
        /** @var User $user */
        $user = auth()?->user();

        if($user->cart->cartProducts->isEmpty()){
            return ApiResponse::success(null, 'Your cart is empty.');
        } 

        $checkout = Checkout::where('user_id', $user->id)->firstOrFail();

        if(!$checkout->billing_address_id || !$checkout->shipping_address_id){
            return ApiResponse::error('Please select billing and shipping address.', 200);
        }

        $checkout->delivery_type = $request->type;
        $checkout->name = $request->has('name') ? $request->name : $user->name;;
        $checkout->contact = $request->has('contact') ? $request->contact : '';
        $checkout->secret = $request->has('secret') ? $request->secret : Str::random(6);
        $checkout->note = $request->has('note') ? $request->note : '';
        $checkout->datetime = $request->has('datetime') ? $request->datetime : '';
        $checkout->pickup_address_id = $request->picking;
        $checkout->save();

        return ApiResponse::success(CheckoutResource::make($checkout), 'Checkout delivery ok.');
    }

    public function payment(Request $request)
    {
        $payModeIds = array_column(config('settings.pay_modes'), 'id');
        $request->validate([
            'pay_mode' => 'required|string|in:'.implode(',', $payModeIds),
        ]);        
        
        /** @var User $user */
        $user = auth()?->user();

        if($user->cart->cartProducts->isEmpty()){
            return ApiResponse::success(null, 'Your cart is empty.');
        } 

        $checkout = Checkout::where('user_id', $user->id)->firstOrFail();
        $checkout->pay_mode = $request->pay_mode;
        $checkout->save();

        return ApiResponse::success(CheckoutResource::make($checkout), 'Checkout pay mode ok.');
    }
}

