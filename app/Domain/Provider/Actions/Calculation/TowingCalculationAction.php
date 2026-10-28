<?php

namespace App\Domain\Provider\Actions\Calculation;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Services\Calculation\InsideTowingCalculationService;
use App\Domain\Provider\Services\Calculation\OutsideTowingCalculationService;
use App\Domain\Towing\Actions\FetchTowingCityLineAction;
use App\Domain\Towing\Actions\FetchTowingFromCityAction;
use App\Support\Enum\TowingDomain;
use Illuminate\Support\Facades\Cache;

class TowingCalculationAction
{


    private FetchTowingFromCityAction $fetchTowingFromCityAction;
    private FetchTowingCityLineAction $fetchTowingCityLineAction;

    public function __construct(FetchTowingFromCityAction $fetchTowingFromCityAction, FetchTowingCityLineAction $fetchTowingCityLineAction)
    {
        $this->fetchTowingFromCityAction = $fetchTowingFromCityAction;
        $this->fetchTowingCityLineAction = $fetchTowingCityLineAction;
    }

    /**
     * @param array $data
     * @param ProviderSubService $towing_service
     * @return mixed
     */
    public function handle(array $data , ProviderSubService $towing_service ): mixed
    {
        if($towing_service->towing_domain == TowingDomain::INSIDE && isset($data['destination_location']))
        {
            $cache_key = implode(',',$data['start_location']) . implode(',',$data['destination_location']) ."_id:$towing_service->id";

            $result = Cache::tags('calculations')->remember($cache_key,60*60*2, fn() => $this->getCalculationResult($towing_service,$data));
        }
        elseif($towing_service->towing_domain == TowingDomain::OUTSIDE && isset($data['destination_city_id']) && isset($data['from_city_id']))
        {
            $start_city         =  $this->fetchTowingFromCityAction->handle($data);
            $destination_city   = $this->fetchTowingCityLineAction->handle($data['destination_city_id'],$data['from_city_id'],$towing_service->towing_type_id);

            $result = $this->getOutsideCalculationResult($towing_service,$start_city,$destination_city);
        }

        return isset($result) ? $result : [];
    }

    private function getCalculationResult($towing_service,$data): array
    {
        return (new InsideTowingCalculationService($towing_service,$data['start_location'], $data['destination_location']))->getResultsValue();
    }

    private function getOutsideCalculationResult($towing_service,$start_city , $destination_city): array
    {
        return (new OutsideTowingCalculationService($towing_service,$start_city, $destination_city))->getResultsValue();
    }
}
