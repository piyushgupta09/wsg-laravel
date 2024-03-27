<?php

namespace Fpaipl\Panel\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Fpaipl\Panel\Events\ReloadDataEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Fpaipl\Panel\Notifications\AdminNotification;

class AdminNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected $title,
        protected $message,
    ) {}

    public function handle(): void
    {
        $admin = User::find(1);
        $admin->notify(new AdminNotification($this->title, $this->message));
        sleep(5);
        ReloadDataEvent::dispatch(config('pusher.message'));
    } 
}
