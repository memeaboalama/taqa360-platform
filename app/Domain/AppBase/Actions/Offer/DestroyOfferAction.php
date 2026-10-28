<?php

namespace App\Domain\AppBase\Actions\Offer;

use App\Domain\AppBase\Repositories\OfferRepository;
use App\Support\Enum\StatusEnum;

class DestroyOfferAction
{
    protected OfferRepository $offerRepository;


    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }


    public function handle(int $id): bool
    {
        return $this->offerRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::INACTIVE->value]);
    }
}
