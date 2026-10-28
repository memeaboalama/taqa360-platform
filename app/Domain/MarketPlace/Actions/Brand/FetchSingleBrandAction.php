<?php

namespace App\Domain\MarketPlace\Actions\Brand;

use App\Domain\MarketPlace\Repositories\BrandRepository;

class FetchSingleBrandAction
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->brandRepository->findByCols(['id' => $id]);
    }
}
