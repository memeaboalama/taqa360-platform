<?php

namespace Domain\Admin\Actions\Dashboard\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Enum\StatusEnum;
use App\Support\Services\Auth\AuthService;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Collection;

class FetchMerchants
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantRepository->getAllMerchantsAsBuilder(['id','name','company_name','email','phone','dialcode','status','created_at'],[]);
    }

    public function fetchCollection(): Collection
    {
        return $this->merchantRepository->all(cols:['id', 'company_name'],condition:["status"=>StatusEnum::ACTIVE->value]);
    }
}

