<?php

namespace App\Domain\AppBase\Actions\Offer;

use App\Domain\AppBase\Repositories\OfferRepository;

class FetchSingleOfferAction
{
    protected OfferRepository $offerRepository;


    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->offerRepository->findByCols(['id' => $id]);
    }
}
