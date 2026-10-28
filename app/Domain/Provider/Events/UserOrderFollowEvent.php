<?php

namespace App\Domain\Provider\Events;

use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Resources\ProviderOrderResource;
use App\Domain\Provider\Resources\ProviderResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOrderFollowEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ProviderOrder $providerOrder;

    /**
     * Create a new event instance.
     */
    public function __construct(ProviderOrder $providerOrder)
    {
        $this->providerOrder = $providerOrder;
    }

    public function broadcastAs(): string
    {
        return 'user-order-follow';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'provider'    =>  new ProviderResource($this->providerOrder?->provider),
                'order'    =>  new ProviderOrderResource($this->providerOrder),
            ]
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user-order.'.$this->providerOrder?->id),
        ];
    }
}
