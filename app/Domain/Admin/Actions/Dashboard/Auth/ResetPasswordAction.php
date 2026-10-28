<?php

namespace App\Domain\Admin\Actions\Dashboard\Auth;

use Domain\Admin\Repositories\AdminRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    protected AdminRepository $adminRepository;


    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $admin = $this->adminRepository->findByCols(['email'=>$data['email']]);
        $admin->update([
            'password' => $data['password']
        ]);
        Auth::guard('admin')->login($admin);
        return $admin;
    }
}
