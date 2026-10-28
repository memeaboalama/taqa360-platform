<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;

class FetchEmployeeAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;
    protected BranchRepository $branchRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository, BranchRepository $branchRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $merchant_id, int $branch_id, array $otherAssignedEmployees = []): array|\Illuminate\Database\Eloquent\Collection
    {
        $manager_id = $this->branchRepository->findByCols(['id'=>$branch_id])?->manager_id;

        return $this->merchantEmployeeRepository->getEmployeesByMerchantId($merchant_id, $manager_id, $otherAssignedEmployees);
    }

}

