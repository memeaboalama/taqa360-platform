<?php

namespace App\Domain\Provider\Actions\Category;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchProviderCategoryAppAction
{


    private ProviderCategoryRepository $providerCategoryRepository;

    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }


    public function handle(): Collection
    {
       return $this->providerCategoryRepository->all(cols:['id', 'title','selection'],condition:["status"=>StatusEnum::ACTIVE->value]);
    }
}
