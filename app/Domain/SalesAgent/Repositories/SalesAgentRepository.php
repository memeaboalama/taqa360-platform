<?php

namespace App\Domain\SalesAgent\Repositories;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Driver\Models\Driver;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SalesAgentRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(SalesAgent $model)
    {
        parent::__construct($model);
    }

    public function filter($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->newQuery();

        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['sales_agent_id']))
            $query->where('id', $filters['sales_agent_id']);

        return $query;
    }


    public function getAllSalseAgentsAsBuilder(array $cols, array $with, array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, $conditions, 'desc');
    }

    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
    }


    public function getSalesAgentTotalOrderReport(int $sales_agent_id)
    {
        $ordersInfo = MerchantOrder::select(
                DB::raw('COUNT(*) as totalOrders'),
                DB::raw('SUM(total_after_vat) as totalAmount'),
                DB::raw('SUM(total_after_vat * sales_agents.taken_commission / 100) as totalCommission'),
                DB::raw('(SELECT COUNT(*) FROM promocodes WHERE sales_agent_id = ' . $sales_agent_id . ') as totalPromocodes'),
                DB::raw('COUNT(DISTINCT merchant_orders.user_id) as distinctUsersCount')
            )
            ->join('promocodes', 'merchant_orders.promocode_id', '=', 'promocodes.id')
            ->join('sales_agents', 'promocodes.sales_agent_id', '=', 'sales_agents.id')
            ->where('sales_agents.id', $sales_agent_id)
            ->first();

        if (!$ordersInfo) {
            return [
                'totalOrders' => 0,
                'totalAmount' => 0,
                'totalCommission' => 0,
                'totalPromocodes' => 0,
                'distinctUsersCount' => 0
            ];
        }

        return [
            'totalOrders' => $ordersInfo->totalOrders,
            'totalAmount' => $ordersInfo->totalAmount,
            'totalCommission' => $ordersInfo->totalCommission,
            'totalPromocodes' => $ordersInfo->totalPromocodes,
            'distinctUsersCount' => $ordersInfo->distinctUsersCount
        ];
    }



    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }
}
