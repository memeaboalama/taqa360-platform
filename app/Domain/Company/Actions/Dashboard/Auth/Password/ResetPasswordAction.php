<?php

namespace App\Domain\Company\Actions\Dashboard\Auth\Password;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $company = $this->companyRepository->findByCols(['phone'=>$data['phone']]);
        $company->update([
            'password' => $data['password']
        ]);
        Auth::guard('company')->login($company);
        return $company;
    }
}
