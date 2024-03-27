<?php

namespace Fpaipl\Shopy\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Fpaipl\Shopy\Models\Order;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Mail\OrderCreateMail;
use Fpaipl\Shopy\Mail\OrderUpdateMail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Fpaipl\Authy\Http\Resources\AddressResource;
use Fpaipl\Shopy\Http\Resources\PaymentResource;
use Fpaipl\Shopy\Http\Resources\DeliveryResource;
use Fpaipl\Shopy\Http\Resources\OrderProductResource;

class SendOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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
            // if order status is pending then send new order email, else send order update email
            if ($this->order->status === 'pending') {
                return (new OrderCreateMail($user, $this->order))
                    ->to($user->email)
                    ->subject('New Order Created #' . $this->order->oid);
            } else {
                return (new OrderUpdateMail($user, $this->order))
                    ->to($user->email)
                    ->subject('Order #' . $this->order->oid . ' has been Updated');
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
            'subject' => $this->order->status === 'pending' ? 'Order Created' : 'Order Updated',
            'data' => [
                'id' => $this->order->id,
                'total' => $this->order->total,
                'amount' => $this->order->amount,
                'tax' => $this->order->tax,
                'status' => $this->order->status,
                'approved_payment' => $this->order->approvedPayments(), // total amt approved
                'billing_address' => $this->order->billing_address,
                'order_products'=> OrderProductResource::collection($this->order->orderProducts),
                'delivery'=> DeliveryResource::collection($this->order->orderDeliveries),
                'payments'=>PaymentResource::collection($this->order->orderPayments),
            ],
        ];
    }
}
