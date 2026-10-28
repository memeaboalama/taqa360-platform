<?php

namespace App\Domain\Admin\Actions\Dashboard\Auth;

use Domain\Admin\Repositories\AdminRepository;

class UpdateProfileAction
{
    protected AdminRepository $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function handle(int $adminId ,array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->adminRepository->update($adminId, $data);
    }
}
