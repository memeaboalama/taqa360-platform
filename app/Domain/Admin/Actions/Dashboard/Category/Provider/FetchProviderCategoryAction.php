<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Provider;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchProviderCategoryAction
{
    protected ProviderCategoryRepository $providerCategoryRepository;


    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }

    public function handle(): Collection
    {
        return $this->providerCategoryRepository->all(cols:['id', 'title'],condition:["status"=>StatusEnum::ACTIVE->value]);

    }
}
