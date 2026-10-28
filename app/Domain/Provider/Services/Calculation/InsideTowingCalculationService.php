<?php

namespace App\Domain\Provider\Services\Calculation;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Services\Calculation\TowingCalculation;

class InsideTowingCalculationService extends TowingCalculation
{


    public function __construct(ProviderSubService $towing_service,array $start_location, array $destination_location)
    {
        $this->towing_service = $towing_service;
        $this->start_location = implode(',',$start_location);
        $this->destination_location = implode(',',$destination_location);
        $this->init();
    }

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setOpenPriceValue();
        $this->setDirectionInformation();
        $this->setDistanceValue();
        $this->setDurationValue();
        $this->setVatValue();
        $this->setTotalPriceValue();
        $this->setResultsValue();
    }

    public function setResultsValue(): void
    {
        $open_price = $this->getOpenPriceValue();
        $direction_info =  $this->getDirectionInformation();
        $distance_in_km = $direction_info['distance']['text'];
        $distance_in_km_value = $direction_info['distance']['value'];
        $duration_in_min = $direction_info['duration']['text'];
        $duration_in_min_value = $direction_info['duration']['value'];
        $start_address  = $direction_info['start_address'];
        $end_address    = $direction_info['end_address'];
        $total_price    = $this->getTotalPriceValue();
        $vat            = $this->getVatValue();
        $this->results = [
            'open_price'        =>  $open_price,
            'total_price'       =>  $total_price,
            'distance_in_km'    =>  $distance_in_km,
            'distance_in_km_value'    =>  $distance_in_km_value,
            'duration_in_min'   =>  $duration_in_min,
            'duration_in_min_value'   =>  $duration_in_min_value,
            'vat'               =>  $vat,
            'start_address'     =>  $start_address,
            'end_address'       =>  $end_address,
        ];
    }

    public function setTotalPriceValue($value = null): void
    {
        if($value) $this->total_price_value = $value;

        $this->total_price_value = $this->getOpenPriceValue() + $this->getDurationValue() + $this->getDistanceValue() + $this->getVatValue();
    }

}
