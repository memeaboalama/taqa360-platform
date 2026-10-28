<?php

namespace App\Domain\Admin\Actions\Dashboard\Employee;

use Domain\Admin\Models\Admin;
use Domain\Admin\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeeAction
{
    protected AdminRepository $adminRepository;

    public function __construct()
    {
        $this->adminRepository = new AdminRepository(new Admin);
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $admin = $this->adminRepository->updateEmployee($id, $data);
        $admin->roles()->detach();
        $admin->assignRole($data['role']);
        return $admin;
    }
}
