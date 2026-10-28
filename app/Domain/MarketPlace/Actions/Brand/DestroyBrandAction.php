<?php

namespace App\Domain\MarketPlace\Actions\Brand;

use App\Domain\MarketPlace\Repositories\BrandRepository;

class DestroyBrandAction
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function handle(int $id): bool
    {
        return $this->brandRepository->destroy($id);
    }
}
