<?php

namespace Fpaipl\Shopy\Http\Livewire;

use Livewire\Component;
use Fpaipl\Shopy\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Fpaipl\Shopy\Notifications\SendPaymentNotification;

class OrderPayment extends Component
{
    public $paymentId;
    public $payment;
    public $order;
    public $orderUser;
    public $currrentRoute;

    public function mount($modelId)
    {
        $this->paymentId = $modelId;
        $this->payment = Payment::findOrFail($this->paymentId);
        $this->order = $this->payment->order;
        $this->orderUser = $this->order->user;
        $this->currrentRoute = 'payments.show';
    }

    public function markPaymentChecked($paymentId)
    {
        try {
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = Auth::user()->id;
            $payment->checked_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->save();
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been checked successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentRejected($paymentId)
    {
        try {
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = Auth::user()->id;
            $payment->checked_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->status = Payment::STATUS[2];
            $payment->save();
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been rejected successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentApproved($paymentId)
    {
        try {
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->approved_by = Auth::user()->id;
            $payment->approved_at = DB::raw('CURRENT_TIMESTAMP');
            $payment->status = Payment::STATUS[1];
            $payment->save();
            $this->orderUser->notify(new SendPaymentNotification($this->order, $payment));
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been approved.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function markPaymentUnApproved($paymentId)
    {
        try {
            $payment = Payment::where('id', $paymentId)->firstOrFail();
            $payment->checked_by = null;
            $payment->checked_at = null;
            $payment->status = Payment::STATUS[0];
            $payment->save();
            $this->orderUser->notify(new SendPaymentNotification($this->order, $payment));
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'success',
                'text' => 'Payment has been unapproved.'
            ]);
        } catch (\Exception $e) {
            Log::error(get_class($this) . ':' . $e->getMessage());
            return redirect()->route($this->currrentRoute, $this->paymentId)->with('toast', [
                'class' => 'error',
                'text' => 'Some issue occurred.'
            ]);
        }
    }

    public function render()
    {
        return view('shopy::livewire.order-payment');
    }
}
