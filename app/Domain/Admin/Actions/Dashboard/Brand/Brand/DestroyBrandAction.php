<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Brand;

use App\Domain\AppBase\Repositories\BrandRepository;

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
