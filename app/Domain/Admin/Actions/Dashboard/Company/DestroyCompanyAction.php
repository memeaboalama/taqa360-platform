<?php

namespace App\Domain\Admin\Actions\Dashboard\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;

class DestroyCompanyAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    public function handle(int $id): bool
    {
        return $this->companyRepository->destroy($id);
    }

}
