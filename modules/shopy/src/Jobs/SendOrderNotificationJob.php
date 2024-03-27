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
use Fpaipl\Shopy\Notifications\SendOrderNotification;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventType;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct($eventType, $event)
    {
        $this->eventType = $eventType;
        $this->order =$event->order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::find($this->order->user_id);

        Notification::send($users, new SendOrderNotification($this->eventType, $this->order));
    }
}
