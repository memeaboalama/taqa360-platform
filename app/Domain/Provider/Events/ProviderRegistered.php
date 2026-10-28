<?php

namespace App\Domain\Provider\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class ProviderRegistered
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable
     */
    private Authenticatable $provider;

    /**
     * Create a new event instance.
     *
     * @param Authenticatable $provider
     * @return void
     */
    public function __construct(Authenticatable $provider)
    {
        $this->provider = $provider;
    }
}
