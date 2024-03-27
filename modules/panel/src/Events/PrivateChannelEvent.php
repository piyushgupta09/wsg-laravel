<?php

namespace Fpaipl\Panel\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateChannelEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $event,
        public string $message,
        private string $recevierIds
    ) {}

    public function broadcastOn()
    {
        $privateChannels = [];
        foreach (explode(',', $this->recevierIds) as $recevierId) {
            $privateChannels[] = new PrivateChannel(config('pusher.private-channel').$recevierId);
        }
        return $privateChannels;
    }

    public function broadcastAs(): string
    {
        return $this->event;
    }
}
