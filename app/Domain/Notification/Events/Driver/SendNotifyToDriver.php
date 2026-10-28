<?php

namespace App\Domain\Notification\Events\Driver;

use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Resources\ProviderOrderResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotifyToDriver implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private array $provider;
    private ProviderOrder $providerOrder;

    /**
     * Create a new event instance.
     */
    public function __construct(array $provider,ProviderOrder $providerOrder)
    {
        $this->provider = $provider;
        $this->providerOrder = $providerOrder;
    }
    public function broadcastWith(): array
    {
        return [
            'data' => [
              'order'    =>  new ProviderOrderResource($this->providerOrder)
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new-towing-order';
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('providers.'.$this->provider['id']),
        ];
    }
}
