<?php

namespace App\Domain\Merchant\Actions\Order;


use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderMerchantAction
{
    private MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }


    public function handle(array $data, string $type, MerchantEmployee $user)
    {

        $paginate = $data["per_page"] ?? 10;

        $branches = $user?->branch()->whereType($type)->pluck('merchant_branches.id')->toArray();

        return $this->merchantOrderRepository->getMerchantOrderByBranchType($type, $user, $branches, $paginate);

    }

    public function show(int $id): ?Model
    {

        return $this->merchantOrderRepository->findByCols
        (cols: ["merchant_id" => auth()->user()->merchant_id, 'id' => $id], with: ['address', 'services', 'employee']);

    }

    public function history(MerchantEmployee $user, array $data)
    {
        $paginate = $data["per_page"] ?? 10;
        $branches = $user?->branch->pluck('id')->toArray();
        return $this->merchantOrderRepository->getMerchantOrderByBranch($user, $branches, $paginate);

    }

    public function update(array $data): ?Model
    {

        return $this->merchantOrderRepository->update($data["order_id"], ["status" => $data["status"]]);

    }

    public function find_employee_order(array $data)
    {
        return $this->merchantOrderRepository->find_employee_order($data);
    }
}
