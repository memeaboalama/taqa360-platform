<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Home;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class StatsAction
{
    protected MerchantOrderRepository $orderRepository;
    protected BranchRepository $branchRepository;
    protected MerchantEmployeeRepository $merchantEmployeeRepository;
    protected MerchantCategoryRepository $merchantCategoryRepository;
    protected ItemRepository $itemRepository;


    public function __construct(
        MerchantOrderRepository $orderRepository
        ,BranchRepository $branchRepository
        ,ItemRepository $itemRepository
        ,MerchantEmployeeRepository $merchantEmployeeRepository
        ,MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->branchRepository = $branchRepository;
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
        $this->merchantCategoryRepository = $merchantCategoryRepository;
        $this->itemRepository = $itemRepository;
    }

    public function handle(int $merchant_id) : array
    {
        return [
            'ordersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id]),
            'pendingOrdersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id, 'status' => MerchantOrderStatuses::PENDING]),
            'completedOrdersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id, 'status' => MerchantOrderStatuses::PREVIOUS]),
            'cancelledOrdersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id, 'status' => MerchantOrderStatuses::CANCELLED]),
            'readyOrdersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id, 'status' => MerchantOrderStatuses::READY]),
            'expiredOrdersCount' => $this->orderRepository->count(['merchant_id' => $merchant_id, 'status' => MerchantOrderStatuses::EXPIRED]),
            'branchesCount' => $this->branchRepository->count(['merchant_id' => $merchant_id]),
            'employeesCount' => $this->merchantEmployeeRepository->count(['merchant_id' => $merchant_id]),
            'categoriesCount' => $this->merchantCategoryRepository->count(['merchant_id' => $merchant_id]),
            'itemCount' => $this->itemRepository->countMenuItems($merchant_id),
        ];
    }
}
