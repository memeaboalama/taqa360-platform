<?php

namespace App\Domain\ElmApp\Actions\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Database\Eloquent\Model;

class FetchCompaniesAction
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    public function handle(array $data)
    {
        
        // $validConditions = ['status', 'company_id']; 
        // $conditions = [];
    
        // foreach ($validConditions as $condition) {
        //     if (isset($data[$condition])) {
        //         $conditions[$condition] = $data[$condition];
        //     }
        // }
        
        $paginate = $data["per_page"] ?? 10;
        $conditions = isset($data["status"]) ? ['status' => $data["status"]] : [];

        return $this->companyRepository->getAllCompaniesAsBuilder(cols:['*'], with:[], conditions:$conditions)
        ->get()
        ->paginate($paginate);
    }
}
