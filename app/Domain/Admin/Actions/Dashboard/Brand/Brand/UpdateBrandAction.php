<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Brand;

use App\Domain\AppBase\Repositories\BrandRepository;

class UpdateBrandAction
{
    protected BrandRepository $brandRepository;


    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->brandRepository->update($id, $data);
    }
}
