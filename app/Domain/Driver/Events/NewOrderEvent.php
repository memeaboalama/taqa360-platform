<?php

namespace App\Domain\Driver\Events;

use App\Domain\Driver\Resources\Order\OrderResource;
use App\Domain\Employee\Resources\Order\OrderDetailsResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, Queueable, SerializesModels;

    private $driver;
    private $Order;

    /**
     * Create a new event instance.
     */
    public function __construct($driver, $Order)
    {
        $this->driver = $driver;
        $this->Order = $Order;
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'order' =>  new OrderResource($this->Order)
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new-order';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('drivers.' . $this->driver->id),
        ];
    }
}
