<?php

namespace App\Domain\Provider\Actions\Auth;


use App\Domain\Provider\Models\Provider;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DetectProviderAction
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
     * @return Model|null
     */
    public function handle(array $data): ?Model
    {
        if(isset($data['location'])) $data['location'] = new Point($data['location']['latitude'],$data['location']['longitude'],Srid::WGS84->value);

        if($provider = $this->providerRepository->findByCols(cols: ['phone'=>$data['phone']],select : ((new Provider())->getAvailableData()))) return $provider;

        return $this->providerRepository->store($data);
    }
}
