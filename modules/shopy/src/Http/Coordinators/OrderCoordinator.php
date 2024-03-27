<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Actions\CreateOrder;
use Fpaipl\Shopy\Http\Resources\OrderResource;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Shopy\Http\Resources\OrdersCollection;
use Fpaipl\Shopy\Notifications\SendOrderNotification;

class OrderCoordinator extends Coordinator
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return OrdersCollection::collection($orders->sortByDesc('created_at'));
    }

    public function pending()
    {
        $orders = Order::where('user_id', auth()->user()->id)->pending()->get();
        return OrdersCollection::collection($orders->sortByDesc('created_at'));
    }

    public function completed()
    {
        $orders = Order::where('user_id', auth()->user()->id)->completed()->get();
        return OrdersCollection::collection($orders->sortByDesc('updated_at'));
    }

    public function cancelled()
    {
        $orders = Order::where('user_id', auth()->user()->id)->cancelled()->get();
        return OrdersCollection::collection($orders->sortByDesc('updated_at'));
    }

    public function show(Request $request, Order $order)
    {
        return json_encode([
            'success' => true,
            'data' => new OrderResource($order)
        ]);
    }

    public function store() 
    {
        $authUser = auth()->user();
        $checkout = $authUser->checkout;

        try{
            
            if($checkout->delivery_type == Delivery::TYPE[0]) {
                $deliverable_id = $checkout->pickup_address_id;
                $deliverable_type = 'Fpaipl\Shopy\Models\PickupAddress';
            } else {
                $deliverable_id = $checkout->shipping_address_id;
                $deliverable_type = 'Fpaipl\Authy\Models\Address';
            }

            $order = CreateOrder::create($authUser, [
                'type' => $checkout->delivery_type,
                'pay_mode' => $checkout->pay_mode,
                'coupon_id' => $checkout->coupon_id,
                'coupon_value' => $checkout->coupon_value,
                'delivery_mode' => Delivery::MODE[0],
                'deliverable_id' => $deliverable_id,
                'deliverable_type' => $deliverable_type,
                'billing' => $checkout->billing_address_id,
                'shipping' => $checkout->shipping_address_id,
                'name' => isset($checkout->name) ? $checkout->name : 'No Name',
                'contact' => isset($checkout->contact) ? $checkout->contact : 'No Contact',
                'secret' => $checkout->secret ?? Str::random(6),
                'datetime' => isset($checkout->datetime) ? $checkout->datetime : Carbon::now()->addDays(config('settings.delivery_days')),
                'note' => isset($checkout->note) ? $checkout->note : 'No Note',
            ]);
           
            if($order){
                $response = true; 
                $message = 'Order ' . $order->id . ' created successfully.';

                // Add tags to order
                $orderTags = [];
                $orderTags[] = $order->oid;
                $orderTags[] = $order->total;
                foreach ($order->orderProducts as $orderProduct) {
                    $product = $orderProduct->product;
                    $orderTags[] = $product->name;
                    $orderTags[] = $product->code;
                    foreach ($product->productOptions as $option) {
                        $orderTags[] = $option->name;
                    }
                    foreach ($product->productRanges as $range) {
                        $orderTags[] = $range->name;
                    }
                }
                $order->tags = $orderTags;
                $order->save();

                // send order notification to user
                $order->user->notify(new SendOrderNotification($order));

            } else {
                $response = false;
                $message = 'Some issue occurred while creating order.';
            }
        } catch(\Exception $e){
            $response = false;
            $message = $e->getMessage();
            Log::error('An error occurred while creating order: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }        
         
        return response()->json([
            'success' => $response,
            'message' => $message,
            'data' => isset($order) ? new OrderResource($order) : null
        ]);
    }

    public function activeOrder()
    {
        $order = Order::where('user_id', auth()->user()->id)->latest()->first();
        return new OrderResource($order);
    }
}
