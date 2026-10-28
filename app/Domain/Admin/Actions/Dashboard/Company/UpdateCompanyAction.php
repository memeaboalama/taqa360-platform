<?php

namespace App\Domain\Admin\Actions\Dashboard\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;

class UpdateCompanyAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->companyRepository->update($id, $data);
    }
}
