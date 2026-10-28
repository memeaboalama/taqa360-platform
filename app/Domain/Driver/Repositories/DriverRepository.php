<?php

namespace App\Domain\Driver\Repositories;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Enum\Elm\ElmDriverStatuses;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Driver\Models\Driver;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Driver $modal)
    {
        parent::__construct($modal);
    }

    public function filter($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->newQuery();
            
        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['driver_id']))
            $query->where('id', $filters['driver_id']);

        if (isset($filters['driver_status']) && $filters['driver_status'] != null)
        {
            if ($filters['driver_status'] == "approved")
            {
                $query->where('is_complete', 1);
            } elseif ($filters['driver_status'] == "pending") {
                $query->where('is_complete', 0)->where('status', '!=', 'rejected');
            } elseif ($filters['driver_status'] == "rejected") {
                $query->where('status', 'rejected');
            }
        }

        if (isset($filters['network_status']))
        {
            if ($filters['network_status'] == "online")
            {
                $query->where('network_status', 1);
            }elseif ($filters['network_status'] == "offline"){
                $query->where('network_status', 0);
            }
        }
        if (isset($filters['company_id']) && $filters['company_id'] != null)
        {
            $query->whereHas('company', function($q) use($filters){
                $q->where('company_id', $filters['company_id']);
            });
        }

        return $query;
    }


    public function getAllDriversAsBuilder(array $cols, array $with, array $conditions = []): \Illuminate\Database\Eloquent\Builder
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

    public function searchByCompany(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('company_id', auth()->guard('company')->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
    }

    public function getDriverTotalOrderReport(int $driver_id)
    {
        $report =  $this->modal->select([
                DB::raw('drivers.id, drivers.name, drivers.phone'),
                DB::raw('SUM(IF(merchant_orders.payment_method = "COD",merchant_orders.delivery_fee,null)) as totalCashOrders'),
                DB::raw('COUNT(IF(merchant_orders.payment_method = "COD",merchant_orders.id,null)) as cashOrdersCount'),
                DB::raw('SUM(IF(merchant_orders.payment_method = "COD",(merchant_orders.delivery_fee * merchant_orders.commission_from_driver / 100),null)) as totalCashCommissionCollected'),
                DB::raw('SUM(IF(merchant_orders.payment_method != "COD",merchant_orders.delivery_fee,null)) as totalOnlinePaymentOrders'),
                DB::raw('COUNT(IF(merchant_orders.payment_method != "COD",merchant_orders.id,null)) as onlinePaymentOrdersCount'),
                DB::raw('SUM(IF(merchant_orders.payment_method != "COD",(merchant_orders.delivery_fee * merchant_orders.commission_from_driver / 100),null)) as totalOnlinePaymentCommissionCollected'),
            ])
            ->where('merchant_orders.status', MerchantOrderStatuses::PREVIOUS->value)
            ->where('merchant_orders.driver_id', $driver_id)
            ->join('merchant_orders', function($join){
                $join->on('drivers.id','=','merchant_orders.driver_id');
            })
            ->first()->toArray();
        $cashCommissionDueToDriver = $report['totalCashOrders'] - $report['totalCashCommissionCollected'];
        $onlinePaymentCommissionDueToDriver = $report['totalOnlinePaymentOrders'] - $report['totalOnlinePaymentCommissionCollected'];
        return array_merge($report, [
            'cashCommissionDueToDriver' => $cashCommissionDueToDriver ?? 0,
            'onlinePaymentCommissionDueToDriver' => $onlinePaymentCommissionDueToDriver ?? 0,
            'totalOrders' => $report['cashOrdersCount'] + $report['onlinePaymentOrdersCount'] ?? 0,
            'totalCollected' => $report['totalCashOrders'] + $report['totalOnlinePaymentOrders'] ?? 0,
            'totalCommission' => $report['totalCashCommissionCollected'] + $report['totalOnlinePaymentCommissionCollected'] ?? 0,
            'totalDueToDriver' => $cashCommissionDueToDriver + $onlinePaymentCommissionDueToDriver ?? 0
        ]);
    }

    public function getDriversByElmStatus(array $filters, $paginate = 20)
    {
        $query = $this->modal->newQuery();

        if (isset($filters['status'])) {
            $query->whereIn('status', [$filters['status']]);
        }

        if (isset($filters['network_status'])) {
            $query->whereIn('network_status', [$filters['network_status']]);
        }
        if (isset($filters['company_id']) && $filters['company_id'] != null) {
            $query->where('company_id', $filters['company_id']);
        }
       
        $this->applyElmStatusFilters($query, $filters);
        $this->applyDateConditionFilters($query, $filters);

        if (isset($filters['eats_drivers']) && $filters['eats_drivers'] == true) {
            $query->whereDoesntHave('company');
        }
        return $query->orderBy('created_at', 'DESC')->cursorPaginate($paginate);
    }
    private function applyElmStatusFilters(Builder &$query, array $filters)
    {
        if (!isset($filters['elm_status'])) {
            return;
        }

        $status = $filters['elm_status'];

        switch ($status) {
            case 'accepted':
                $query->where('elm_upload_status', ElmDriverStatuses::ACCEPTED)
                        ->whereNull('elm_errors');
                break;
            case 'rejected':
                $query->where('elm_upload_status', '!=', ElmDriverStatuses::ACCEPTED)
                        ->whereNotNull('elm_errors');
                break;
            case 'needs_updating':
                $query->where('elm_upload_status',[
                        ElmDriverStatuses::NEW->value,
                        ElmDriverStatuses::NEEDS_UPDATEING->value
                    ]);
                break;
            case 'errors':
                $query->whereNotNull('elm_errors');
                break;     
            default:
                break;
        }
    }

    private function applyDateConditionFilters(Builder &$query, array $filters)
    {
        if (!isset($filters['date_condition']) || !isset($filters['date'])) {
            return;
        }

        $dateCondition = $filters['date_condition'];
        $date = $filters['date'];

        switch ($dateCondition) {
            case 'equal':
                $query->whereDate('created_at', $date);
                break;
            case 'gt':
                $query->whereDate('created_at', '>=', $date);
                break;
            case 'between':
                $query->whereDate('created_at', '>=', $date)
                    ->whereDate('created_at', '<=', $filters['to_date']);
                break;
            default:
                break;
        }
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }
}
