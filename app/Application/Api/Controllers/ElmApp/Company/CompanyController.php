<?php

namespace App\Application\Api\Controllers\ElmApp\Company;

use App\Application\Api\Requests\ElmApp\Company\FetchCompaniesRequest;
use App\Application\Api\Requests\ElmApp\Company\UpdateCompanyRequest;
use App\Domain\Admin\Actions\Dashboard\Company\UpdateCompanyAction;
use App\Domain\Company\Repositories\CompanyEmployeeRepository;
use App\Domain\ElmApp\Actions\Company\FetchCompaniesAction;
use App\Domain\ElmApp\resources\CompanyResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;


class CompanyController extends Controller
{
    use ApiResponseTrait;

    protected CompanyEmployeeRepository $CompanyRepository;


    public function __construct(CompanyEmployeeRepository $CompanyRepository)
    {
        $this->CompanyRepository = $CompanyRepository;
    }

    public function fetch(FetchCompaniesRequest $request, FetchCompaniesAction $fetchCompaniesAction)
    {
      $companies = $fetchCompaniesAction->handle($request->validated());
      return $this->responseData([
        'companies' => CompanyResource::collection($companies)
      ]);
    }

    // public function updateCompanyInfo(UpdateCompanyRequest $request): \Illuminate\Http\JsonResponse
    // {
    //     $data = $request->validated();
    //     unset($data['Company_id']);

    //     $updateData = array_filter($data, fn($value) => $value !== null);
    //     $this->CompanyRepository->update($request->validated()['Company_id'],$updateData);
      
    //     return $this->responseData(
    //       code: 200, 
    //       msg: "Company Update successfully"
    //     );
    // }


}
