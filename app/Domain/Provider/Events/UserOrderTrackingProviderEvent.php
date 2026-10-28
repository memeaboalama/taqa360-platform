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

class UserOrderTrackingProviderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ProviderOrder $providerOrder;
    private array $location;

    /**
     * Create a new event instance.
     */
    public function __construct(ProviderOrder $providerOrder,array $location)
    {
        $this->providerOrder = $providerOrder;
        $this->location = $location;
    }

    public function broadcastAs(): string
    {
        return 'user-order-tracking-provider';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'provider'    =>  new ProviderResource($this->providerOrder?->provider),
                'order'    =>  new ProviderOrderResource($this->providerOrder),
                'location'              => [
                    'latitude'           =>  $this->location['latitude'],
                    'longitude'          =>  $this->location['longitude'],
                ],
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
            new PrivateChannel('user-order-tracking-provider.'.$this->providerOrder?->id),
        ];
    }
}
