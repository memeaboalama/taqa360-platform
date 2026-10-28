<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;

class UpdateBranchEmployeesAction
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $branch_id, array $data = [])
    {
        return $this->branchRepository->updateBranchEmployees($branch_id, $data);
    }

}

