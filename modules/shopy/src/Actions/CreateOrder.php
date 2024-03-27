<?php

namespace Fpaipl\Shopy\Actions;

use App\Models\User;
use Illuminate\Support\Str;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Coupon;
use Fpaipl\Shopy\Models\Delivery;
use Fpaipl\Shopy\Models\OrderTax;
use Fpaipl\Shopy\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Models\OrderCoupon;
use Fpaipl\Shopy\Models\OrderProduct;
use Illuminate\Support\Facades\Validator;

class CreateOrder
{
    public static function create(User $user, array $data)
    {
        // Log::info('CreateOrder: ' . json_encode($data));
        $validator = Validator::make($data, [
            'billing' => 'required',
            'type' => 'required',
            'name' => 'required',
            'contact' => 'required',
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors: ', $validator->errors()->toArray());
            return null; // Or throw an exception
        }
        
        DB::beginTransaction();

        try {

            $billingAddress = $user->addresses()->find($data['billing']);

            $orderId = Str::random(6);
            while (Order::where('oid', $orderId)->exists()) {
                $orderId = Str::random(6);
            }

            $order = Order::create([
                'oid' => $orderId,
                'user_id' => $user->id,
                'pay_mode' => $data['pay_mode'],
                'billing_address' => $billingAddress->print,
            ]);

            // Coupon Code
            if ($data['coupon_id'] && $data['coupon_value']) {

                /** @var User $user */
                $user = auth()->user();
                
                $coupon = Coupon::find($data['coupon_id']);

                if ($coupon && $coupon->checkEligibility($user, $order)) {
                    $orderCoupon = OrderCoupon::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'coupon_id' => $coupon->id,
                        'value' => $data['coupon_value'],
                    ]);
                } else {
                    $orderCoupon = null;
                    Log::error('Coupon not found or not eligible for user');
                }
            }

            $address = $data['deliverable_type']::findOrFail($data['deliverable_id']);
            // Log::info('Address: ' . $address->print);
            Delivery::create([
                'order_id' => $order->id,
                'type' => $data['type'],
                'name' => $data['name'],
                'contact' => $data['contact'],
                'secret' => $data['secret'],
                'datetime' => $data['datetime'],
                'note' => $data['note'],
                'shipping_address' => $address->print,
                'expected_on' => isset($data['datetime']) ? $data['datetime'] : now()->addDays(config('settings.delivery_days')),
            ]);

            if ($order) {
                foreach ($user->cart->cartProducts as $cartIndex => $cartProduct) {
                    
                    $product = $cartProduct->product;
                    $cartItemsCount = $cartProduct->cartItems->count();
                    // Log::info('Cart Items Count: ' . $cartItemsCount);
                    $orderProduct = OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $cartProduct->product_id,
                        'suborder_id' => $order->oid . '_' . ($cartIndex + 1),
                        'quantity' => $cartProduct->quantity,
                        'skus' => $cartItemsCount,
                    ]);

                    if ($orderProduct) {

                        foreach ($cartProduct->cartItems as $item) {
                            $productRangeRate = $item->productRange->rate;
                            $itemTotal = $productRangeRate * $item->quantity;
                            $itemTax = round($itemTotal * ($product->tax->gstrate / 100), 2);
                            $itemAmount = $itemTotal - $itemTax;
                            
                            OrderItem::create([
                                'order_product_id' => $orderProduct->id,
                                'product_option_id' => $item->product_option_id,
                                'product_range_id' => $item->product_range_id,
                                'mrp' => $item->productRange->mrp,
                                'rate' => $productRangeRate,
                                'quantity' => $item->quantity,
                                'amount' => $itemAmount,
                                'tax' => $itemTax,
                                'total' => $itemTotal,
                            ]);

                            $orderProduct->amount += $itemAmount;
                            $orderProduct->tax += $itemTax;
                            $orderProduct->total += $itemTotal;
                            $orderProduct->save();
                        }

                        $cartProduct->delete();

                        $orderTax = [
                            'order_product_id' => $orderProduct->id,
                            'tax_id' => $product->tax_id,
                            'hsncode' => $product->tax->hsncode,
                            'gstrate' => $product->tax->gstrate,
                        ];
                        if ($cartProduct->taxtype == 'intrastate') {
                            $orderTax['cgst'] = $orderProduct->tax / 2;
                            $orderTax['sgst'] = $orderProduct->tax / 2;
                        } elseif ($cartProduct->taxtype == 'interstate') {
                            $orderTax['igst'] = $orderProduct->tax;
                        } elseif ($cartProduct->taxtype == 'union-territory') {
                            $orderTax['utgst'] = $orderProduct->tax;
                        }
                        OrderTax::create($orderTax);
                        
                        $order->amount = $order->amount + $orderProduct->amount;
                        $order->tax = $order->tax + $orderProduct->tax;
                        $order->total = $order->total + $orderProduct->total;
                        $order->save();

                    }
                }
            }

            DB::commit();
            // OrderCreateEvent::dispatch($order);
            return $order;
        } catch(\Exception $e){
            DB::rollBack();
            Log::error('An error occurred while creating order: ' . $e->getMessage());
            throw $e; // or return an error message
        }      
         
    }
}
