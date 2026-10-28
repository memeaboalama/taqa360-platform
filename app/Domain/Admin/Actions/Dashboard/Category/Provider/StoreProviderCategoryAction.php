<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Provider;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;

class StoreProviderCategoryAction
{
    protected ProviderCategoryRepository $providerCategoryRepository;


    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->providerCategoryRepository->store($data);
    }
}
