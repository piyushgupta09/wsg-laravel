<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Support\Facades\Log;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;

class DeliveryCoordinator extends Coordinator
{
    public $response = '';
    public $message = '';
    
    public function show(Delivery $delivery)
    {
        $delivery = Delivery::findOrFail($delivery->id);
        return [
            'id' => $delivery->id,
            'mode' => $delivery->mode,
            'status' => $delivery->status,
            'shipping_address' =>[
                'fname' => $delivery->shippingAddress->fname,
                'lname' => $delivery->shippingAddress->lname,
                'contact_no' => $delivery->shippingAddress->contact_no,
                'line1' => $delivery->shippingAddress->line1,
                'line2' => $delivery->shippingAddress->line2,
                'district' => $delivery->shippingAddress->district,
                'state' => $delivery->shippingAddress->state,
                'country' => $delivery->shippingAddress->country, 
                'pincode' => $delivery->shippingAddress->pincode,
                'is_primary' => $delivery->shippingAddress->is_primary,
                'sb_same' => $delivery->shippingAddress->sb_same,
                'type' => $delivery->shippingAddress->type
            ],
            'pending' => $delivery->created_at,
            'shipped_at' => $delivery->shipped_at,
            'delivered_at' => $delivery->delivered_at,
            'rejected_at' => $delivery->rejected_at,
        ];
    }

   public function updateDelivery(Request $request)
    {
        $request->validate([
            'order' => 'required|exists:orders,oid',
            'name' => 'nullable|string|min:1|max:255',
            'contact' => 'nullable|string|min:10|max:15',
            'datetime' => 'nullable|date_format:Y-m-d H:i:s',
            'note' => 'nullable|string|min:1|max:255',
            'secret' => 'nullable|string|min:1|max:15',
        ]);

        $order = Order::where('oid', $request->order)->with('orderDeliveries')->first();
        $delivery = $order->orderDeliveries->first();

        if($order->status == 'completed'){
            return ApiResponse::error('Order is already completed', 400);
        }

        if($delivery->status != 'pending'){
            return ApiResponse::error('Delivery is already processed', 400);
        }

        // $delivery->type = $request->type;
        $delivery->name = $request->name ?? $delivery->name;
        $delivery->contact = $request->contact ?? $delivery->contact;
        $delivery->datetime = $request->datetime ?? $delivery->datetime;
        $delivery->note = $request->note ?? $delivery->note;
        $delivery->secret = $request->secret ?? $delivery->secret;
        $delivery->update();
      
        return ApiResponse::success('Delivery updated successfully', 200);
    }
}