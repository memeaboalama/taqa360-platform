<?php

namespace App\Support\Actions\Dashboard\Spatie\Role;

use App\Support\Repositories\Spatie\RoleRepository;

class UpdateRoleAction
{
    protected RoleRepository $roleRepository;


    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['name'] = $data['name'] . '_' . $data['team_id'];
        $role = $this->roleRepository->update($element_id, $data);
        if (isset($data['permissions']))
        {
            $role->permissions()->detach();
            $role->syncPermissions($data['permissions']);
        }
        return $role;
    }
}
