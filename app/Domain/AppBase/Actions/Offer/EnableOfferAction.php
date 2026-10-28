<?php

namespace App\Domain\AppBase\Actions\Offer;

use App\Domain\AppBase\Repositories\OfferRepository;
use App\Support\Enum\StatusEnum;

class EnableOfferAction
{
    protected OfferRepository $offerRepository;


    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }


    public function handle(int $id,$expired_at): bool
    {
        return $this->offerRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::ACTIVE->value,'expired_at'=>$expired_at]);
    }
}
