<?php

namespace App\Domain\MarketPlace\Actions\Brand;

use App\Domain\MarketPlace\Repositories\BrandRepository;

class FetchBrandAction
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function handle(array $data = [])
    {
        $condition = isset($data['condition']) ? $data['condition'] : [];

        if(isset($data['paginated']) && $data['paginated'] == true)
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;
            return $this->brandRepository->paginate(cols: ['*'], paginate: $per_page, condition: $condition);
        }

        return $this->brandRepository->all(condition: $condition);
    }
}
