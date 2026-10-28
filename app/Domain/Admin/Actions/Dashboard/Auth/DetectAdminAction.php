<?php

namespace App\Domain\Admin\Actions\Dashboard\Auth;

use Domain\Admin\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectAdminAction
{
    private AdminRepository $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function detectAdminByPhone(array $data): Model|bool|null
    {
        return $this->adminRepository->findByCols(cols: ['phone' => $data["phone"]],with:[]);
    }

    public function detectAdminByEmail(array $data): Model|bool|null
    {
        return $this->adminRepository->findByCols(cols: ['email' => $data["email"]],with:[]);
    }

    public function detectAdminApi(array $data): Model|bool|null
    {
        if (!Auth::guard('admin')->attempt(request()->only('email','password'))) {
            return false;
        }
        return $this->adminRepository->findByCols(cols: ['email' => $data["email"]],with:[]);
    }
}
