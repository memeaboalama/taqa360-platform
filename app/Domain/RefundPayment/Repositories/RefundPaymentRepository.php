<?php

namespace App\Domain\RefundPayment\Repositories;

use App\Support\Repositories\Main\EloquentMainRepository;
use App\Domain\AppBase\Models\RefundPayment;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RefundPaymentRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(RefundPayment $modal)
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

        if (isset($filters['refundPayment_id']))
            $query->where('id', $filters['refundPayment_id']);

     
        if (isset($filters['customer_id']) && $filters['customer_id'] != null)
        {
            $query->whereHas('customer', function($q) use($filters){
                $q->where('customer_id', $filters['customer_id']);
            });
        }
        return $query;
    }


    public function getAllRefundPaymentsAsBuilder(array $cols, array $with, array $conditions = []): \Illuminate\Database\Eloquent\Builder
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

    public function searchByCustomer(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('customer_id', auth()->guard('customer')->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
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
