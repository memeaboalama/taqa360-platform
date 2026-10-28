<?php

namespace App\Support\Actions\Dashboard\Spatie\Role;

use App\Support\Repositories\Spatie\RoleRepository;

class FetchRolesAction
{
    protected RoleRepository $roleRepository;


    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle(array $cols)
    {
        return $this->roleRepository->getAsBuilder($cols)->get();
    }

    public function fetchFilter(array $cols= [], array $filter = [])
    {
        return $this->roleRepository->getAsBuilder($cols)->where($filter)->get();
    }
}
