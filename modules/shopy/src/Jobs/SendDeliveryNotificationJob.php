<?php

namespace Fpaipl\Shopy\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Fpaipl\Shopy\Notifications\SendDeliveryNotification;

class SendDeliveryNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventType;

    public $delivery;

    /**
     * Create a new job instance.
     */
    public function __construct($eventType, $event)
    {
        $this->eventType = $eventType;
        $this->delivery =$event->delivery;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::find($this->delivery->order->user_id);

        Notification::send($users, new SendDeliveryNotification($this->eventType, $this->delivery));

    }
}
