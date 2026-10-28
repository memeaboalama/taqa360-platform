<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Provider;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;

class DestroyProviderCategoryAction
{
    protected ProviderCategoryRepository $providerCategoryRepository;


    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->providerCategoryRepository->destroy($id);
    }
}
