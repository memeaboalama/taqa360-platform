<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\Provider;
use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class GetProviderCurrentOrderAction
{
    private ProviderRepository $providerRepository;

    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    /**
     * @param Provider $provider
     * @return Model|null
     */
    public function handle(Provider $provider): ?Model
    {

        return $this->providerRepository->fetchProviderCurrentOrder($provider);
    }
}
