<?php

namespace App\Domain\MarketPlace\Actions\Api\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FetchMerchantCategoryAction
{
    private MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;


    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = [])
    {
        $collect_cat_id = DB::table('merchant_branch_categories')
            ->where(
                ['merchant_id' => $data["merchant"]],
                ["merchant_branch_id" => $data["branch"]],
                ['status' => StatusEnum::ACTIVE->value]
            )->pluck('merchant_category_id')->toArray();
        return $this->categoryRepository->get_categories_by_branch(cols: ['id', 'title'], condition: ["merchant_id" => $data["merchant"]], order: 'DESC',ids:$collect_cat_id);


    }
}
