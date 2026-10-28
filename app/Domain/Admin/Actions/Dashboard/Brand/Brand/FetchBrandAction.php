<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Brand;

use App\Domain\AppBase\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchBrandAction
{
    protected BrandRepository $brandRepository;


    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }


    public function handle(): Collection
    {
        return $this->brandRepository->all();
    }
}
