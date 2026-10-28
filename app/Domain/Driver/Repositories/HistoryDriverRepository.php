<?php

namespace App\Domain\Driver\Repositories;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Enum\Elm\ElmDriverStatuses;
use App\Support\Repositories\Main\EloquentMainRepository;
use App\Domain\MarketPlace\Models\DriverHistory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class HistoryDriverRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(DriverHistory $modal)
    {
        parent::__construct($modal);
    }

    public function filter($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {

        $query = $this->modal
            ->with($with)
            ->newQuery();

        if (isset($filters['driver_id']))
            $query->where('driver_id', $filters['driver_id']);


        if (isset($filters['company_id']) && $filters['company_id'] != null)
        {
            $query->whereHas('driver', function($q) use($filters){
                $q->where('company_id', $filters['company_id']);
            });
        }
        if (isset($filters['date_condition']) && !empty($filters['date']))
        {
            if ($filters['date_condition'] == "equal")
                $query->whereDate('created_at', $filters['date']);

            if ($filters['date_condition'] == "gt")
                $query->whereDate('created_at','>=', $filters['date']);

            if ($filters['date_condition'] == "between" && !empty($filters['to_date']))
                $query->whereDate('created_at','>=',$filters['date'])
                    ->whereDate('created_at','<=',$filters['to_date']);
        }

        return $query;
    }


    public function getAllHistoryDriversAsBuilder(array $cols, array $with, array $conditions = []): \Illuminate\Database\Eloquent\Builder
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
