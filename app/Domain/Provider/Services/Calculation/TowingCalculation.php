<?php

namespace App\Domain\Provider\Services\Calculation;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Towing\Models\TowingCity;
use App\Domain\Towing\Models\TowingLine;
use App\Support\Services\Google\DirectionService;

abstract class TowingCalculation
{
    public  const DurationDivisionValue = 120;
    public const DistanceDivisionValue = 1000;

    public array $results;
    public ProviderSubService $towing_service;
    public string|TowingCity $start_location;
    public string|TowingLine $destination_location;
    public array $direction_information;
    public float $distanceValue;
    public float $durationValue;
    public float $vat;
    public float $total_price_value;
    public float $open_price;

    abstract public function init();
    abstract public function setResultsValue();

    abstract function setTotalPriceValue($value = null): void;

    public function getResultsValue(): array
    {
        return $this->results;
    }

    public function setDirectionInformation($value = null): void
    {
        if($value) $this->direction_information = $value;

        $this->direction_information = (new DirectionService($this->start_location,$this->destination_location))->filtered_results();
    }
    public function getDirectionInformation(): array
    {
        return $this->direction_information;
    }

    /**
     * @return float
     */
    public function getDistanceValue(): float
    {
        return $this->distanceValue;
    }

    public function setDistanceValue($value = null): void
    {
        if($value) $this->distanceValue = $value;
        $direction_info = $this->getDirectionInformation();

        $towing_price_per_km = $this->towing_service?->towing_price_per_km;

        $this->distanceValue = ($direction_info['distance']['value'] / self::DistanceDivisionValue) * $towing_price_per_km;
    }

    public function getDurationValue(): float
    {
        return $this->durationValue;
    }

    public function setDurationValue($value = null): void
    {
        if($value) $this->durationValue = $value;

        $direction_info = $this->getDirectionInformation();

        $towing_price_per_minute = $this->towing_service?->towing_price_per_minute;

        $this->durationValue = ($direction_info['duration']['value'] / self::DurationDivisionValue) * $towing_price_per_minute;
    }

    public function getVatValue(): float
    {
        return $this->vat;
    }

    public function setVatValue($value = null): void
    {
        if($value) $this->vat = $value;

        $this->vat =  0;
    }
    public function getOpenPriceValue(): float
    {
        return $this->open_price;
    }
    public function setOpenPriceValue($value = null): void
    {
        if($value) $this->open_price = $value;

        $this->open_price = round($this->towing_service->towing_open_price,2);
    }

    public function getTotalPriceValue(): float
    {
        return round($this->total_price_value,2);
    }
}
