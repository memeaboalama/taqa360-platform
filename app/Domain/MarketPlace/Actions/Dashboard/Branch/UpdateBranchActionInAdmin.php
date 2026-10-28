<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;

class UpdateBranchActionInAdmin
{
    protected BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }


    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
            $model= $this->branchRepository->update($element_id, $data);
            $package_type_services = collect($data['package_type_services'] ?? [])->where('status', 'active');
            foreach ($package_type_services as $option) 
            {
                $model->merchant_branch_type_services()->create([
                    'type_service_id' => $option['type_service_id'],
                    'package_id' => $option['package_id'],
                    'commission' => $option['commission'],
                    'discount_rate' => $option['discount_rate'],
                    'desc' => $option['desc'],
                    'status' => $option['status'],
                ]);
            }
        return $model;
    }

}
