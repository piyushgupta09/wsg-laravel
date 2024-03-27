<?php

namespace Fpaipl\Panel\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $event;
    private $channel;
    
    public $title;
    public $message;

    public function __construct($channel, $event, $title, $message)
    {
        $this->event = $event;
        $this->channel = $channel;

        $this->title = $title;
        $this->message = $message;
        Log::info('PushNotification: ' . $this->event . ' ' . $this->channel . ' ' . $this->title . ' ' . $this->message);
    }

    
    public function broadcastOn()
    {
        return new Channel('my-channel');
        // return new Channel($this->channel);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'my-event';
        // return $this->event;
    }
}
