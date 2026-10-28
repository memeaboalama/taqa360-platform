<?php

namespace App\Domain\Admin\Actions\Dashboard\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;

class FetchSingleCompanyAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->companyRepository->findByCols(['id' => $id]);
    }
}
