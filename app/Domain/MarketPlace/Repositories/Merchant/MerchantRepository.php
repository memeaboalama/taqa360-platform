<?php

namespace App\Domain\MarketPlace\Repositories\Merchant;


use App\Domain\MarketPlace\Filters\MerchantBranchFilter;
use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Support\Enum\StatusEnum;
use Auth;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Collection;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MerchantRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Merchant $model)
    {
        parent::__construct($model);
    }

    public function getByUserName(string $user_name, int $branchId, array $data = []): Model|null
    {
        if (isset($data["latitude"]) && isset($data['longitude'])) {
            $location = [$data["latitude"], $data['longitude']];
            return $this->modal->withWhereHas(
                'branches',
                fn($query) => $query->select('id', 'merchant_id', 'type', 'address_name', 'has_scheduled_services')->getDistanceTo($location)->where('merchant_branches.id', '=', $branchId)
            )
                ->select('user_name', 'id', 'description', 'company_name')
                ->where('user_name', $user_name)
                ->first();
        }

        return $this->modal->withWhereHas(
            'branches',
            fn($query) => $query->select('id', 'merchant_id', 'type', 'address_name', 'has_scheduled_services')->where('merchant_branches.id', '=', $branchId)
        )
            ->select('user_name', 'id', 'description', 'company_name')
            ->where('user_name', $user_name)
            ->first();
    }

    public function getAllMerchantsAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function filter($filters = [], array $cols, array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->select($cols)
            ->newQuery();


        if (isset($filters['is_open']) && $filters['is_open'] != null) {
            $query->whereHas('branches', function ($q) use ($filters) {
                $q->where('status', $filters['is_open']);
            });
        }
        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['region_id']) && $filters['region_id'] != null) {
            $query->whereHas('branches', function ($q) use ($filters) {
                $q->where('region_id', $filters['region_id']);
            });
        }

        if (isset($filters['city_id']) && $filters['city_id'] != null) {
            $query->whereHas('branches', function ($q) use ($filters) {
                $q->where('city_id', $filters['city_id']);
            });
        }

        if (isset($filters['district_id']) && $filters['district_id'] != null) {
            $query->whereHas('branches', function ($q) use ($filters) {
                $q->where('distract_id', $filters['district_id']);
            });
        }

        if (isset($filters['merchant_id']))
            $query->where('id', $filters['merchant_id']);


        if (isset($filters['date_condition'])) {
            if ($filters['date_condition'] == "equal")
                $query->whereDate('created_at', $filters['date']);

            if ($filters['date_condition'] == "gt")
                $query->whereDate('created_at', '>=', $filters['date']);

            if ($filters['date_condition'] == "between")
                $query->whereDate('created_at', '>=', $filters['date'])
                    ->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query;
    }

    public function fetchTopSales(int $count)
    {
        return $this->modal
            ->select(
                [
                    DB::raw('merchants.id, merchants.company_name, merchants.status'),
                    DB::raw('SUM(merchant_order_items.qty) as totalItemsQty'),
                    DB::raw('SUM(merchant_orders.total_after_discount) as totalPrice'),
                ]
            )
            ->join('merchant_orders', 'merchants.id', '=',  'merchant_orders.merchant_id')
            ->join('merchant_order_items', 'merchants.id', '=',  'merchant_order_items.merchant_id')
            ->orderBy('totalItemsQty', 'desc')
            ->take($count)
            ->get();
    }

    public function searchByNearestBranch(array $cols = ['*'], array $data = [], array $geo = [], array $sorting = [], int $paginate = 20)
    {
        try {
            // Log::info("MerchantRepository searchByNearestBranch geo " );
            // Log::info($geo);
            $data = $this->modal
                ->with('checkBranchesAvailability')
                ->select('id', 'company_name', 'user_name', 'description', 'phone')
                ->has('branches')
                ->where('status', StatusEnum::ACTIVE->value)
                ->filter(new MerchantFilter($data))
                //    ->whereHas('activeBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);

                //    })
                //    ->orWhereHas('inactiveBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);
                //    })
                ->where(function ($query) use ($geo, $cols) {
                    $query->whereHas('activeBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols)->getIntDistanceTo($geo);
                    })
                        ->orWhereHas('inactiveBranch', function ($q) use ($geo, $cols) {
                            $q->select($cols)->getIntDistanceTo($geo);
                        });
                })


                ->with('activeBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                ->get()
                ->map(function (Merchant $row) {
                    return [
                        'id' => $row->id,
                        'company_name' => $row->company_name,
                        'user_name' => $row->user_name,
                        'description' => $row->description,
                        'logo' => $row->logo,
                        'rate' => $row->rate,
                        'avgPrice' => $row->avgPrice,
                        'branch' => ($row->activeBranch) ? $row->activeBranch : $row->inactiveBranch,
                        // 'userBalance' => 0
                        // 'userBalance' => auth('sanctum')->user()->hasWallet(auth('sanctum')->id().'_'.$row->id.'_'.$row->phone)  ? auth('sanctum')->user()->getWallet(auth('sanctum')->id().'_'.$row->id.'_'.$row->phone)->balanceFloatNum:0,
                        'userBalance' => auth('sanctum')->check() ?
                            (
                                auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone) ?
                                auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone)->balanceFloatNum
                                : 0
                            )
                            : 0
                    ];
                })
                ->sortBy('id')
                ->sortBy('distance')
                ->sortBy(function ($row) {
                    return $row['branch']?->distance;
                })
                ->sortByDesc(function ($row) use ($sorting) {
                    if (in_array("rating", $sorting)) {
                        return $row['rate'];
                    }
                })
                ->sortBy(function ($row) use ($sorting) {
                    if (in_array("price", $sorting)) {
                        return $row['avgPrice'];
                    }
                    if (in_array("delivery", $sorting)) {
                        return $row['branch']?->deliveryTime;
                    }
                })
                ->paginate($paginate);
        } catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
        }
        return $data;
    }
    public function searchByNearestPackUpBranch(array $cols = ['*'], array $data = [], array $geo = [], array $sorting = [], int $paginate = 20)
    {
        try {
            $data = $this->modal
                ->with('checkBranchesAvailability')
                ->select('id', 'company_name', 'user_name', 'description', 'phone')
                ->has('branches')
                ->where('status', StatusEnum::ACTIVE->value)
                ->filter(new MerchantFilter($data))
                ->whereHas('branches', function ($v) use ($cols) {
                    $v->whereHas('merchant_branch_type_services', function ($c) use ($cols) {
                        $c->where('type_service_id', 2)->where('status', StatusEnum::ACTIVE->value);
                    });
                })
                //    ->whereHas('activeBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);

                //    })
                //    ->orWhereHas('inactiveBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);
                //    })
                ->where(function ($query) use ($geo, $cols) {
                    $query->whereHas('activeBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols)->getIntDistanceTo($geo);
                    })
                        ->orWhereHas('inactiveBranch', function ($q) use ($geo, $cols) {
                            $q->select($cols)->getIntDistanceTo($geo);
                        });
                })


                ->with('activeBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                ->get()
                ->map(function (Merchant $row) {
                    return [
                        'id' => $row->id,
                        'company_name' => $row->company_name,
                        'user_name' => $row->user_name,
                        'description' => $row->description,
                        'logo' => $row->logo,
                        'rate' => $row->rate,
                        'avgPrice' => $row->avgPrice,
                        'branch' => ($row->activeBranch) ? $row->activeBranch : $row->inactiveBranch,
                        'userBalance' => auth('sanctum')->check() ?
                            (
                                auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone) ?
                                auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone)->balanceFloatNum
                                : 0
                            )
                            : 0
                    ];
                })
                ->sortBy('id')
                ->sortBy('distance')
                ->sortBy(function ($row) {
                    return $row['branch']?->distance;
                })
                ->sortByDesc(function ($row) use ($sorting) {
                    if (in_array("rating", $sorting)) {
                        return $row['rate'];
                    }
                })
                ->sortBy(function ($row) use ($sorting) {
                    if (in_array("price", $sorting)) {
                        return $row['avgPrice'];
                    }
                    if (in_array("delivery", $sorting)) {
                        return $row['branch']?->deliveryTime;
                    }
                })
                ->paginate($paginate);
        } catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
            Log::error("MerchantRepository searchByNearestPackUpBranch Error ");
            Log::error($exception);
        }
        return $data;
    }


    public function search(array $cols, string $query): Collection
    {
        $term = '%' . mb_strtolower($query) . '%';
        return $this->modal->select($cols)
            ->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(company_name, "$.en"))) LIKE ?', [$term])
            ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(company_name, "$.ar"))) LIKE ?', [$term])
            ->get();
    }



    public function searchByNearestFavouriteBranch(array $cols = ['*'], array $data = [], array $geo = [], array $sorting = [], int $paginate = 20)
    {
        try {
            $data = $this->modal
                ->with('checkBranchesAvailability')
                ->select('id', 'company_name', 'user_name', 'description', 'phone')
                ->has('branches')
                ->whereHas('favs', function ($v) use ($cols) {
                    $v->where('user_id', auth()->user()->id)->select('id as fav_id');
                })
                // ->whereHas('favorites', function ($v) use($cols){
                //     $v->where('id',auth('sanctum')->user()->id);
                // })
                ->where('status', StatusEnum::ACTIVE->value)
                ->filter(new MerchantFilter($data))
                ->whereHas('branches', function ($v) use ($cols) {
                    $v->whereHas('merchant_branch_type_services', function ($c) use ($cols) {
                        $c->where('type_service_id', 2)->where('status', StatusEnum::ACTIVE->value);
                    });
                })
                //    ->whereHas('activeBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);

                //    })
                //    ->orWhereHas('inactiveBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);
                //    })
                ->where(function ($query) use ($geo, $cols) {
                    $query->whereHas('activeBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols)->getIntDistanceTo($geo);
                    })
                        ->orWhereHas('inactiveBranch', function ($q) use ($geo, $cols) {
                            $q->select($cols)->getIntDistanceTo($geo);
                        });
                })


                ->with('activeBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getIntDistanceTo($geo);
                })
                // ->with('favs', function ($q) use ( $cols) {
                //     $q->select('id as fav_id', 'merchant_branch_id as merchant_branch_id')->get();
                //     // $q->select('id', 'merchant_branch_id');
                //     // $q->select($cols);
                // })
                ->with('favs')
                ->get()
                ->map(function (Merchant $row) {
                    return [
                        // 'favs' => $row->favorites,
                        'id' => $row->id,
                        'company_name' => $row->company_name,
                        'user_name' => $row->user_name,
                        'description' => $row->description,
                        'logo' => $row->logo,
                        'rate' => $row->rate,
                        'avgPrice' => $row->avgPrice,
                        'branch' => ($row->activeBranch) ? $row->activeBranch : $row->inactiveBranch,
                        'userBalance' => auth('sanctum')->check() ?
                            (
                                auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone) ?
                                auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone)->balanceFloatNum
                                : 0
                            )
                            : 0
                    ];
                })
                ->sortBy('id')
                ->sortBy('distance')
                ->sortBy(function ($row) {
                    return $row['branch']?->distance;
                })
                ->sortByDesc(function ($row) use ($sorting) {
                    if (in_array("rating", $sorting)) {
                        return $row['rate'];
                    }
                })
                ->sortBy(function ($row) use ($sorting) {
                    if (in_array("price", $sorting)) {
                        return $row['avgPrice'];
                    }
                    if (in_array("delivery", $sorting)) {
                        return $row['branch']?->deliveryTime;
                    }
                })
                // ->paginate($paginate)
            ;
        } catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
            Log::error("MerchantRepository searchByNearestFavouriteBranch Error ");
            Log::error($exception);
        }
        return $data;
    }



    // v2
    public function searchBranch(array $cols = ['*'], array $data = [], array $geo = [], array $sorting = [], int $paginate = 20)
    {
        try {
            // Log::info("MerchantRepository searchByNearestBranch geo " );
            // Log::info($geo);
            $geo = array_filter($geo);
            $query = $this->modal
                ->with('checkBranchesAvailability')
                ->select('id', 'company_name', 'user_name', 'description', 'phone')
                ->has('branches')
                ->where('status', StatusEnum::ACTIVE->value)
                ->filter(new MerchantFilter($data));
            //    ->whereHas('activeBranch', function($q) use($geo, $cols){
            //        $q->select($cols)->getDistanceTo($geo);

            //    })
            //    ->orWhereHas('inactiveBranch', function($q) use($geo, $cols){
            //        $q->select($cols)->getDistanceTo($geo);
            //    })
            if (!empty($geo)) {
                $query
                // ->where(function ($query) use ($geo, $cols) {
                //     $query->whereHas('activeBranch', function ($q) use ($geo, $cols) {
                //         $q->select($cols)->getDistanceTo($geo);
                //     })
                //         ->orWhereHas('inactiveBranch', function ($q) use ($geo, $cols) {
                //             $q->select($cols)->getDistanceTo($geo);
                //         });
                // })
                ->with('activeBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getDistanceTo($geo);
                })
                ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols)->getDistanceTo($geo);
                });
            } else {
                $query->with('activeBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols);
                })
                ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                    $q->select($cols);
                });
            }

            return $data = $query->get()
                ->map(function (Merchant $row) {
                    return [
                        'id' => $row->id,
                        'company_name' => $row->company_name,
                        'user_name' => $row->user_name,
                        'description' => $row->description,
                        'logo' => $row->logo,
                        'rate' => $row->rate,
                        'avgPrice' => $row->avgPrice,
                        'branch' => ($row->activeBranch) ? $row->activeBranch : $row->inactiveBranch,
                        // 'userBalance' => 0
                        // 'userBalance' => auth('sanctum')->user()->hasWallet(auth('sanctum')->id().'_'.$row->id.'_'.$row->phone)  ? auth('sanctum')->user()->getWallet(auth('sanctum')->id().'_'.$row->id.'_'.$row->phone)->balanceFloatNum:0,
                        'userBalance' => auth('sanctum')->check() ?
                            (
                                auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone) ?
                                auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone)->balanceFloatNum
                                : 0
                            )
                            : 0
                    ];
                })
                ->sortBy('id')
                ->sortBy('distance')
                ->sortBy(function ($row) {
                    return $row['branch']?->distance;
                })
                ->sortByDesc(function ($row) use ($sorting) {
                    if (in_array("rating", $sorting)) {
                        return $row['rate'];
                    }
                })
                ->sortBy(function ($row) use ($sorting) {
                    if (in_array("price", $sorting)) {
                        return $row['avgPrice'];
                    }
                    if (in_array("delivery", $sorting)) {
                        return $row['branch']?->deliveryTime;
                    }
                })
                ->paginate($paginate);
        } catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
            Log::error("MerchantRepository searchBranch Error ");
            Log::error($exception->getMessage());
            Log::error($exception);
            return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
        }
        return $data;
    }
    public function searchPackUpBranch(array $cols = ['*'], array $data = [], array $geo = [], array $sorting = [], int $paginate = 20)
    {
        try {
            $geo = array_filter($geo);
            $query = $this->modal
                ->with('checkBranchesAvailability')
                ->select('id', 'company_name', 'user_name', 'description', 'phone')
                ->has('branches')
                ->where('status', StatusEnum::ACTIVE->value)
                ->filter(new MerchantFilter($data))
                ->whereHas('branches', function ($v) use ($cols) {
                    $v->whereHas('merchant_branch_type_services', function ($c) use ($cols) {
                        $c->where('type_service_id', 2)->where('status', StatusEnum::ACTIVE->value);
                    });
                });
                //    ->whereHas('activeBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);

                //    })
                //    ->orWhereHas('inactiveBranch', function($q) use($geo, $cols){
                //        $q->select($cols)->getIntDistanceTo($geo);
                //    })
                if (!empty($geo)) {
                    $query
                    // ->where(function ($query) use ($geo, $cols) {
                    //     $query->whereHas('activeBranch', function ($q) use ($geo, $cols) {
                    //         $q->select($cols)->getDistanceTo($geo);
                    //     })
                    //         ->orWhereHas('inactiveBranch', function ($q) use ($geo, $cols) {
                    //             $q->select($cols)->getDistanceTo($geo);
                    //         });
                    // })
                    ->with('activeBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols)->getDistanceTo($geo);
                    })
                    ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols)->getDistanceTo($geo);
                    });
                } else {
                    $query->with('activeBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols);
                    })
                    ->with('inactiveBranch', function ($q) use ($geo, $cols) {
                        $q->select($cols);
                    });
                }
               return $data = $query->get()
                ->map(function (Merchant $row) {
                    return [
                        'id' => $row->id,
                        'company_name' => $row->company_name,
                        'user_name' => $row->user_name,
                        'description' => $row->description,
                        'logo' => $row->logo,
                        'rate' => $row->rate,
                        'avgPrice' => $row->avgPrice,
                        'branch' => ($row->activeBranch) ? $row->activeBranch : $row->inactiveBranch,
                        'userBalance' => auth('sanctum')->check() ?
                            (
                                auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone) ?
                                auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $row->id . '_' . $row->phone)->balanceFloatNum
                                : 0
                            )
                            : 0
                    ];
                })
                ->sortBy('id')
                ->sortBy('distance')
                ->sortBy(function ($row) {
                    return $row['branch']?->distance;
                })
                ->sortByDesc(function ($row) use ($sorting) {
                    if (in_array("rating", $sorting)) {
                        return $row['rate'];
                    }
                })
                ->sortBy(function ($row) use ($sorting) {
                    if (in_array("price", $sorting)) {
                        return $row['avgPrice'];
                    }
                    if (in_array("delivery", $sorting)) {
                        return $row['branch']?->deliveryTime;
                    }
                })
                ->paginate($paginate);
        } catch (\Exception $exception) {
            if ($exception->getCode() == "22S03") return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
            Log::error("MerchantRepository searchPackUpBranch Error ");
            Log::error($exception->getMessage());
            Log::error($exception);
            return ["status" => false, "message" => "Error:Locationisoutofrange!", "data" => null, "code" => 400];
        }
        return $data;
    }
}
