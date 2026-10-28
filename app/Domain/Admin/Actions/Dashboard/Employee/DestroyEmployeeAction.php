<?php

namespace App\Domain\Admin\Actions\Dashboard\Employee;

use Domain\Admin\Models\Admin;
use Domain\Admin\Repositories\AdminRepository;

class DestroyEmployeeAction
{
    protected AdminRepository $adminRepository;

    public function __construct()
    {
        $this->adminRepository = new AdminRepository(new Admin);
    }

    public function handle(int $id): bool
    {
        return $this->adminRepository->destroy($id);
    }
}
