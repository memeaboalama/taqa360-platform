<?php

namespace App\Domain\Company\Actions\Dashboard\Auth;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectCompanyAction
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data): Model|bool|null
    {
        if (!Auth::guard('company')->attempt(request()->only('email','password'))) {
            return false;
        }

        return $this->companyRepository->findByCols(cols: ['email' => $data["email"]],with:["branch"]);
    }

    public function detectCompanyByPhone(array $data): Model|bool|null
    {
        return $this->companyRepository->findByCols(cols: ['phone' => $data["phone"]],with:[]);
    }
}
