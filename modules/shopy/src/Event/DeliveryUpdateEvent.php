<?php

namespace Fpaipl\Shopy\Event;

use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class DeliveryUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $delivery;

    /**
     * Create a new event instance.
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
