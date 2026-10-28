<?php

namespace App\Domain\Provider\Actions\Dashboard\Provider;


use Domain\Provider\Repositories\ProviderRepository;

class DestroyProviderAction
{
    protected ProviderRepository $providerRepository;


    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }


    public function handle(int $id): bool
    {
        return $this->providerRepository->destroy($id);
    }

}

