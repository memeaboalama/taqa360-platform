<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Provider;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;

class FetchSingleProviderCategoryAction
{
    protected ProviderCategoryRepository $providerCategoryRepository;


    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->providerCategoryRepository->findByCols(['id' => $id]);
    }
}
