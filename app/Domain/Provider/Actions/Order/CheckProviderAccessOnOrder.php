<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\Provider;
use App\Domain\Provider\Models\ProviderOrder;
use Illuminate\Support\Facades\Cache;

class CheckProviderAccessOnOrder
{

    public function __construct()
    {
    }

    /**
     * @param ProviderOrder $order
     * @param Provider $provider
     * @return bool
     */
    public function handle(ProviderOrder $order, Provider $provider): bool
    {
        return isset(Cache::tags('on_order_queue')->get('on_order.'.$order?->id)['id'])  && Cache::tags('on_order_queue')->get('on_order.'.$order?->id)['id'] == $provider->id;
    }
}
