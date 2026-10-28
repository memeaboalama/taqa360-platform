<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Brand;

use App\Domain\AppBase\Repositories\BrandRepository;

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
