<?php

namespace App\Domain\MarketPlace\Repositories\Merchant;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MerchantEmployee;

class MerchantEmployeeRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantEmployee $model)
    {
        parent::__construct($model);
    }

    public function getEmployeesByMerchantId(int $merchant_id, int $manager_id = null, array $otherAssignedEmployees = []): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->builder()
            ->where('merchant_id', $merchant_id)
            ->where('id', '!=', $manager_id)
            ->whereNotIn('id', $otherAssignedEmployees)
            ->get();
    }

    public function getEmployeeByOrderId($order, $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return $this->modal::query()->where('merchant_id', $order->merchant_id)->whereHas('branch', function ($q) use ($order) {
            $q->where('merchant_branches.id', $order->merchant_branch_id);
        })->find($data["employee_id"]);
    }

    public function getMerchantEmployee()
    {
        return  $this->modal::where('merchant_id', auth()->user()->merchant_id)->whereHas('branch', function ($q) {
            return $q->whereIn('merchant_branches.id', auth()->user()->branch->pluck('id'));
        })->get();
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }
}
