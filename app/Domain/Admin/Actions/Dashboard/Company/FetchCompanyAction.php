<?php

namespace App\Domain\Admin\Actions\Dashboard\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchCompanyAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function handle(): Collection
    {
        return $this->companyRepository->all();
    }
}
