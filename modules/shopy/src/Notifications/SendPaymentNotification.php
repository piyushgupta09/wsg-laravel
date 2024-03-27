<?php

namespace Fpaipl\Shopy\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Fpaipl\Shopy\Mail\PaymentCreateMail;
use Fpaipl\Shopy\Mail\PaymentUpdateMail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $payment;

    public function __construct($order, $payment = null)
    {
        $this->order = $order;
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    public function toMail(object $notifiable)
    {
        $user = User::find($notifiable->id);

        if ($user->email !== null && $user->email !== '') {

            if ($this->payment === null) {
                return (new PaymentCreateMail($user, $this->order))
                    ->to($user->email)
                    ->subject('Payment Creation Failed');
            } else {
                if ($this->payment->status === 'rejected') {
                    return (new PaymentUpdateMail($user, $this->order))
                        ->to($user->email)
                        ->subject('Payment of Rs.' . $this->payment->amount . ' rejected against Order #' . $this->order->oid);
                } else if ($this->payment->status === 'approved') {
                    return (new PaymentUpdateMail($user, $this->order))
                        ->to($user->email)
                        ->subject('Payment of Rs.' . $this->payment->amount . ' approved against Order #' . $this->order->oid);
                } else {
                    return (new PaymentCreateMail($user, $this->payment))
                        ->to($user->email)
                        ->subject('New Payment Recevied of Rs.' . $this->payment->amount);
                }
            }

        }

        if ($user->usernameIsMobileNo()) {
            // Send welcome sms
        }  
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->payment->status === 'rejected' ? 'Payment Rejected' : ($this->payment->status === 'approved' ? 'Payment Approved' : 'New Payment'),
            'data' => [
                'id' => $this->payment->id,
                'mode' => $this->payment->mode,
                'reference_id' => $this->payment->reference_id,
                'amount' => $this->payment->amount,
                'payment_date' => $this->payment->date,
                'status' => $this->payment->status,
                'approved_by' => $this->payment->approvedByUser,
                'approved_at' => $this->payment->approved_at,
                'checked_by' => $this->payment->checkedByUser,
                'checked_at' => $this->payment->checked_at,
                'image' => $this->payment->getImage('s200', 'primary'),
                'preview' => $this->payment->getImage('s1200', 'primary'),
            ],
        ];
    }
}
