<?php

namespace Fpaipl\Panel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Fpaipl\Panel\Services\WebPushMessage;
use Fpaipl\Panel\Services\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WebPushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $actionUrl;
    protected $iconUrl;

    public function __construct(
        $title = 'Wsg Brand Notification',
        $body = 'Deshigirl has a new order.',
        $actionUrl = 'https://app.wsgbrand.in/',
        $iconUrl = '/logo.png'
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->actionUrl = $actionUrl;
        $this->iconUrl = $iconUrl;
        Log::info(
            'WebPushNotification',
            [
                'title' => $this->title,
                'body' => $this->body,
                'actionUrl' => $this->actionUrl,
                'iconUrl' => $this->iconUrl,
            ]
        );
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class, 'database'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->icon($this->iconUrl)
            ->action('View App', config('app.client_url'))
            ->data(['url' => $this->actionUrl]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'actionUrl' => $this->actionUrl,
            'iconUrl' => $this->iconUrl,
        ];
    }
}
