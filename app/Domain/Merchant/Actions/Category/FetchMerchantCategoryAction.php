<?php

namespace App\Domain\Merchant\Actions\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchMerchantCategoryAction
{
    private MerchantCategoryRepository $merchantCategoryRepository;

    public function __construct(MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->merchantCategoryRepository->paginate(cols: ['id','title'], paginate: $per_page);
        }
        return $this->merchantCategoryRepository->all(['id','title']);
    }
}
