<?php

namespace App\Domain\AppBase\Actions\Offer;

use App\Domain\AppBase\Repositories\OfferRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchOfferAction
{
    protected OfferRepository $offerRepository;


    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function handle(): Collection
    {
        return $this->offerRepository->all();
    }
}
