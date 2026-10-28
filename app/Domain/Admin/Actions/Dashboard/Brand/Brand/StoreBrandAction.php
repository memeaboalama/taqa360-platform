<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Brand;

use App\Domain\AppBase\Repositories\BrandRepository;
use App\Support\Services\Generator\GenerateCodeService;

class StoreBrandAction
{
    protected BrandRepository $brandRepository;


    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['code'] = "BR_" . GenerateCodeService::randomCode();
        return $this->brandRepository->store($data);
    }
}
