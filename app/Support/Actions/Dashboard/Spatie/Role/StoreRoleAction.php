<?php

namespace App\Support\Actions\Dashboard\Spatie\Role;

use App\Support\Repositories\Spatie\RoleRepository;

class StoreRoleAction
{
    protected RoleRepository $roleRepository;


    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['name'] = $data['name'] . '_' . $data['team_id'];
        $role = $this->roleRepository->store($data);
        if (isset($data['permissions']))
            $role->syncPermissions($data['permissions']);
        return $role;
    }
}
