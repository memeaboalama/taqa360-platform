<?php

namespace App\Support\Actions\Dashboard\Spatie\Permission;

use App\Support\Repositories\Spatie\PermissionRepository;

class FetchPermissionAction
{
    protected PermissionRepository $permissionRepository;


    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }


    public function handle($cols)
    {
        return $this->permissionRepository->get($cols);
    }
}
