<?php

namespace Fpaipl\Shopy\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Fpaipl\Shopy\Mail\DeliveryCreateMail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDeliveryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $delivery;

    public function __construct($order, $delivery = null)
    {
        $this->order = $order;
        $this->delivery = $delivery;
    }

    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {

        $user = User::find($notifiable->id);

        if ($user->email !== null && $user->email !== '') {

            if ($this->delivery === null) {
                return (new DeliveryCreateMail($user, $this->order))
                    ->to($user->email)
                    ->subject('Delivery Creation Failed');
            } else {
                if ($this->delivery->status === 'delivered') {
                    return (new DeliveryCreateMail($user, $this->order))
                        ->to($user->email)
                        ->subject('Order #' . $this->order->oid . ' is dispatched.');
                } else if ($this->delivery->status === 'completed') {
                    return (new DeliveryCreateMail($user, $this->order))
                        ->to($user->email)
                        ->subject('Order #' . $this->order->oid . ' is delivered.');
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
            'subject' => 'Delivery Update',
            'data' => [
                'id' => $this->delivery->id,
                'mode' => $this->delivery->mode,
                'status' => $this->delivery->status,
                'pending' => $this->delivery->created_at,
                'shipped_at' => $this->delivery->shipped_at,
                'delivered_at' => $this->delivery->delivered_at,
                'rejected_at' => $this->delivery->rejected_at,
            ],
            
        ];
    }
}
