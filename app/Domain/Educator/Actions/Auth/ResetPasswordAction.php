<?php

namespace Domain\Educator\Actions\Auth;

use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    protected EducatorRepository $educatorRepository;
    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $admin = $this->educatorRepository->findByCols(['email'=>$data['email']]);
        $admin->update([
            'password' => $data['password']
        ]);
        Auth::guard('educator')->login($admin);
        return $admin;
    }
}
