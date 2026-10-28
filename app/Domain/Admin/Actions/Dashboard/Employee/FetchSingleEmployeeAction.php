<?php

namespace App\Domain\Admin\Actions\Dashboard\Employee;

use Domain\Admin\Models\Admin;
use Domain\Admin\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class FetchSingleEmployeeAction
{
    protected AdminRepository $adminRepository;

    public function __construct()
    {
        $this->adminRepository = new AdminRepository(new Admin);
    }

    public function handle(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->adminRepository->findByCols(['id' => $id]);
    }
}
