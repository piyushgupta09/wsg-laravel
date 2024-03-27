<?php

namespace Fpaipl\Shopy\Http\Coordinators;

use Carbon\Carbon;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Http\Requests\PaymentRequest;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Shopy\Notifications\SendPaymentNotification;

class PaymentCoordinator extends Coordinator
{
    public $response = '';
    public $message = '';
    
    public function store(PaymentRequest $request) 
    {
        $order = Order::where('oid', $request->input('order'))->first();
        
        // Start transaction to ensure data integrity.
        DB::beginTransaction();

        try {
            // Create payment for the given order.
            $payment = Payment::create([
                'order_id' => $order->id,
                'mode' => $request->input('mode'),
                'type' => $request->input('type'), // previous named as 'mode'
                'reference_id' => $request->input('refid'),
                'amount' => $request->input('amount'),
                'date' => Carbon::createFromFormat('Y-m-d', $request->input('date')),
            ]);

            // Upload the file to the payment using media library.
            if ($request->file('reciept')) {
                $payment->addMediaFromRequest('reciept')->toMediaCollection('reciepts');
            }

            DB::commit();
            $response = true; 
            $statusCode = 200;
            $message = 'Payment created successfully.';
            $order->user->notify(new SendPaymentNotification($order, $payment));

        } catch (\Exception $e) {
            DB::rollBack();
            $response = false;
            $statusCode = 500;
            $message = $e->getMessage();
            Log::error("Error creating payment: " . $message);
            $order->user->notify(new SendPaymentNotification($order));
        }  
        
        return response()->json([
            'success' => $response,
            'message' => $message,
        ], $statusCode);
    }

    public function show(Payment $payment)
    {
        $payment = Payment::findOrFail($payment->id);
        return [
            'id' => $payment->id,
            'mode' => $payment->mode,
            'type' => $payment->type, // previous named as 'mode
            'reference_id' => $payment->reference_id,
            'amount' => $payment->amount,
            'payment_date' => $payment->date,
            'status' => $payment->status,
            'approved_by' => $payment->approvedByUser,
            'approved_at' => $payment->approved_at,
            'checked_by' => $payment->checkedByUser,
            'checked_at' => $payment->checked_at,
            'image' => $payment->getImage('s100', 'primary'),
        ];
    }
   
}