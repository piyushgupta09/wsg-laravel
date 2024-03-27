<?php

namespace Fpaipl\Panel\Notifications;

use Illuminate\Bus\Queueable;
use Fpaipl\Panel\Services\WebPushChannel;
use Fpaipl\Panel\Services\WebPushMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppNotification extends Notification implements ShouldQueue
{
    use Queueable; 
 
    public function __construct(
        protected string $title,
        protected string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Hello '.$notifiable->name)
            ->icon('/notification-icon.png')
            ->body('Good, Push Notifications work!')
            ->action('View App', 'notification_action');
    }
}
