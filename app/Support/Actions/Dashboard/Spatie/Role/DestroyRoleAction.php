<?php

namespace App\Support\Actions\Dashboard\Spatie\Role;

use App\Support\Repositories\Spatie\RoleRepository;

class DestroyRoleAction
{
    protected RoleRepository $roleRepository;


    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle(int $roleId): bool
    {
        return $this->roleRepository->destroy($roleId);
    }
}
