<?php

namespace App\Domain\Admin\Actions\Dashboard\Employee;

use Domain\Admin\Models\Admin;
use Domain\Admin\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class StoreEmployeeAction
{
    protected AdminRepository $adminRepository;

    public function __construct()
    {
        $this->adminRepository = new AdminRepository(new Admin);
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['type'] = 'employee';
        $admin = $this->adminRepository->store($data);
        $admin->assignRole($data['role']);
        return $admin;
    }
}
