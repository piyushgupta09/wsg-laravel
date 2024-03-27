<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Fpaipl\Shopy\Notifications\SendPaymentNotification;

class OrderPayments extends Component
{
    public $orderId;
    public $orderSid;
    public $order;
    public $orderUser;
    public $currrentRoute;
    public $actionDisabled;

    public function mount($modelId)
    {
        $this->actionDisabled = false;
        $this->orderId = $modelId;
        $this->order = Order::findOrFail($this->orderId);
        $this->orderSid = $this->order->oid;
        $this->orderUser = $this->order->user;
        $this->currrentRoute = 'orders.show';
    }

    public function markPaymentChecked($paymentId)
    {
        try {
            $this->actionDisabled = true;
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = Auth::user()->id;
            $payment->checked_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->save();
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been checked successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentRejected($paymentId)
    {
        try {
            $this->actionDisabled = true;
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = Auth::user()->id;
            $payment->checked_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->status = Payment::STATUS[2];
            $payment->save();
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been rejected successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentApproved($paymentId)
    {
        try {
            $this->actionDisabled = true;
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->approved_by = Auth::user()->id;
            $payment->approved_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->status = Payment::STATUS[1];
            $payment->save();
            $this->orderUser->notify(new SendPaymentNotification($this->order, $payment));
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been approved.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentUnApproved($paymentId)
    {
        try {
            $this->actionDisabled = true;
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = null;
            $payment->checked_at = null;
            $payment->status = Payment::STATUS[0];
            $payment->save();
            $this->orderUser->notify(new SendPaymentNotification($this->order, $payment));
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been unapproved.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->orderSid)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function render()
    {
        return view('shopy::livewire.order-payments');
    }
}
