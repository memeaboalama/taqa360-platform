<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service\Addon;

use App\Domain\MarketPlace\Repositories\Service\Addon\MerchantAddonCategoryRepository;
use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Domain\MarketPlace\Models\MerchantAddonCategory;

class UpdateAddonCategoryAction
{
    protected MerchantAddonCategoryRepository $merchantAddonCategoryRepository;


    public function __construct(MerchantAddonCategoryRepository $merchantAddonCategoryRepository)
    {
        $this->merchantAddonCategoryRepository = $merchantAddonCategoryRepository;
    }

    public function handle(int $addon_category_id ,array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $addonCategory =  $this->merchantAddonCategoryRepository
            ->findByCols(['id' => $addon_category_id, 'merchant_id' => auth()->guard('merchant')->user()->id]);

        $addonCategory->update($data);
        if(isset($data['addons']))
        {
            $addonCategory->addons()->delete();
            foreach ($data['addons'] as $key => $value)
            {
                $value['merchant_service_id'] = $addonCategory->service()?->first()?->id;
                $addonCategory->addons()->create($value);
            }
        }

        return $addonCategory;
    }
}
