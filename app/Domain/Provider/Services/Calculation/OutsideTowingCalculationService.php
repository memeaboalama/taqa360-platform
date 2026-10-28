<?php

namespace App\Domain\Provider\Services\Calculation;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Towing\Models\TowingCity;
use App\Domain\Towing\Models\TowingLine;
use App\Support\Services\Google\DirectionService;

class OutsideTowingCalculationService extends TowingCalculation
{
    public float $destinationPriceValue;

    public function __construct(ProviderSubService $towing_service,TowingCity $start_city, TowingLine $destination_city)
    {
        $this->towing_service = $towing_service;
        $this->start_location = $start_city;
        $this->destination_location = $destination_city;
        $this->init();
    }

    public function init(): void
    {
        $this->setDestinationPriceValue();
        $this->setOpenPriceValue(0);
        $this->setVatValue(0);
        $this->setTotalPriceValue();
        $this->setResultsValue();
    }

    public function setResultsValue(): void
    {
        $open_price     = $this->getOpenPriceValue(0);
        $distance_in_km = 0;
        $duration_in_min = 0;
        $distance_in_km_value = 0;
        $duration_in_min_value = 0;
        $start_address  = $this->start_location?->title;
        $end_address    = $this->destination_location?->to_city?->title;
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

    public function getDestinationPriceValue(): float
    {
        return $this->destinationPriceValue;
    }

    public function setDestinationPriceValue($destinationPriceValue = null): void
    {
        if($destinationPriceValue) $this->destinationPriceValue = $destinationPriceValue;

        $this->destinationPriceValue = $this->destination_location?->price;
    }

    public function setTotalPriceValue($value = null): void
    {
        $this->total_price_value = $this->getOpenPriceValue() + $this->getDestinationPriceValue() + $this->getVatValue();
    }

}
