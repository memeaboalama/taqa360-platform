<?php

namespace App\Domain\Employee\Actions\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Eloquent\Model;

class FetchOrderAction
{
    private MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }
    public function handle(array $data, MerchantEmployee $user)
    {
        $paginate = $data["per_page"] ?? 10;
        $branches = $user?->branch()?->pluck('merchant_branches.id')->toArray();
        if (!$branches)
        {
            $branches = $user?->branchManager()?->pluck('merchant_branches.id')->toArray();
        }
        return $this->merchantOrderRepository->getMerchantOrderByBranch($user, $branches, $data, $paginate);

    }
    
    public function show(int $id): ?Model
    {
        return $this->merchantOrderRepository->findByCols
        (cols: ["merchant_id" => auth()->guard('employee-api')->user()->merchant_id, 'id' => $id], with: ['orderItems', 'employee', 'driver']);

    }
}
