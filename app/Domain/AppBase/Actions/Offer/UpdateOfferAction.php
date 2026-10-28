<?php

namespace App\Domain\AppBase\Actions\Offer;

use App\Domain\AppBase\Repositories\OfferRepository;

class UpdateOfferAction
{
    protected OfferRepository $offerRepository;


    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->offerRepository->update($id, $data);
    }
}
