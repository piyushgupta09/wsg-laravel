<?php

namespace Fpaipl\Shopy\Event;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CartProductDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $colorSize;
    public $quantity;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $colorSize, $quantity)
    {
        $this->colorSize = $colorSize;
        $this->user = $user;
        $this->quantity = $quantity;
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
