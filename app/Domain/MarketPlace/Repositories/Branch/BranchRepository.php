<?php

namespace App\Domain\MarketPlace\Repositories\Branch;

use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Repositories\GeneralSettingRepository;
use App\Domain\MarketPlace\Filters\MerchantBranchFilter;
use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Pagination\CursorPaginator;

class BranchRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantBranch $model)
    {
        parent::__construct($model);

    }

    public function fetch_nearest_branch_to_user_paginate(
        array $cols = ['*'],
        array $geo = [],
        array $relations = [],
        array $condition = [],
        string $order = 'ASC',
        int $paginate = 10): CursorPaginator| array
    {
        try {
            $data = $this->modal->where($condition)
                ->with($relations)
                ->whereHas('merchant', function($q){
                    $q->where('status', StatusEnum::ACTIVE->value);
                })
                ->filter(new MerchantFilter())
                ->select($cols)
                ->getIntDistanceTo($geo)
                ->orderBy('id', $order)
                ->orderBy('distance', $order)
                ->groupBy('merchant_id')
                ->firstClosedTo()
                ->having('distance','<=', (GeneralSetting::first()?->restaurant_search_capacity * 1000))
                ->cursorPaginate($paginate);
        }catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
        }
        foreach ($data as $key => $datum) {
            $datum->update([
                'distance' => $datum->distance
            ]);

        }
        return $data;

    }

    public function searchNearestBranch(
        array $cols = ['*'],
        array $geo = [],
        array $sorting = [],
        int $paginate = 10): CursorPaginator| array
    {
        try {
            array_walk($cols, function (&$value, $key) {
                $value="merchant_branches.$value";
            });
            $data = $this->modal
                ->joining()
                ->select($cols)
                ->filter(new MerchantBranchFilter())
                ->getIntDistanceTo($geo)
                ->firstClosedTo()
                ->orderBy('merchant_branches.id', "ASC")
                ->orderBy('distance', "ASC")
                ->groupBy('merchant_branches.merchant_id')
                ->applySorting($sorting, $geo)
                ->cursorPaginate($paginate);
        }catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
        }
        return $data;
    }

    public function getAllBranchesAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function findWithWorkDay(array $cols = ['*'], array $relations = [], array $conditions = [])
    {
        return $this->modal::select($cols)->with($relations)->where($conditions)->first();
    }


    public function cartBranch($data, array $cols = ['*'], array $relations = [])
    {
        return $this->modal->query()->with($relations)->select($cols)->where('merchant_id', $data->merchant_id)->find($data->merchant_branch_id);
    }

    public function checkDistance($merchant, $branch, $address)
    {
        return $this->modal->query()->where('merchant_id', $merchant)
            ->getDistanceTo([$address->longitude, $address->latitude])
            ->withinDistanceTo([$address->longitude, $address->latitude], 15000)
            ->find($branch);
    }

    public function updateBranchEmployees(int $branch_id, array $data)
    {
        return $this->builder(['*'], ['employees'])->where('id', $branch_id)->find($branch_id)->employees()->sync($data['employees_ids']);
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }
}
