<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Illuminate\Support\Facades\DB;

class StoreMerchantCategoryAction
{
    protected MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $category =  $this->categoryRepository->store($data);
        DB::table('merchant_services_category')->insert([
            'merchant_category_id' => $category->id,
            'merchant_service_id' => $data['menu_id'],
            'merchant_id' => $data['merchant_id'],
        ]);
        return $category;
    }

}
