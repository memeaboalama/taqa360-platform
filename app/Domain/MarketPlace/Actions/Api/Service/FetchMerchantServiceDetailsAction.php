<?php

namespace App\Domain\MarketPlace\Actions\Api\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantCategoryAddonRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchMerchantServiceDetailsAction
{
    private MerchantCategoryAddonRepository $merchantCategoryAddonRepository;


    public function __construct(MerchantCategoryAddonRepository $merchantCategoryAddonRepository)
    {
        $this->merchantCategoryAddonRepository = $merchantCategoryAddonRepository;


    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(int $merchantService)
    {
        $relations = ['addons' => function ($q) {
            $q->active()->select('id', 'title', 'addon_category_id', 'price');
        }];
        return $this->merchantCategoryAddonRepository
            ->all_active(relations: $relations, condition: ['merchant_service_id' => $merchantService]);


    }
}
