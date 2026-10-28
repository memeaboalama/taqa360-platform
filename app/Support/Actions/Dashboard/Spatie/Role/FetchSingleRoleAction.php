<?php

namespace App\Support\Actions\Dashboard\Spatie\Role;

use App\Support\Repositories\Spatie\RoleRepository;

class FetchSingleRoleAction
{
    protected RoleRepository $roleRepository;


    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle($cols, $with = []): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->roleRepository->findByCols($cols, $with);
    }
}
