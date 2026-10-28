<?php

namespace App\Domain\Provider\Actions\Location;


use App\Domain\Provider\Models\Provider;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateProviderLocationAction
{
    private ProviderRepository $providerRepository;

    /**
     * @param ProviderRepository $providerRepository
     */
    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    /**
     * @param array $data
     * @param $provider
     * @return Model|null
     */
    public function handle(array $data,$provider): ?Model
    {
        if(isset($data['location'])) $data['location'] = new Point($data['location']['latitude'],$data['location']['longitude'],Srid::WGS84->value);

        return $this->providerRepository->updateWithReturn($provider->id,$data);
    }
}
