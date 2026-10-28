<?php

namespace App\Domain\MarketPlace\Repositories\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MarketPlaceCategory;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MerchantOrderRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public array $months;
    public $settings;

    public function __construct(MerchantOrder $model)
    {

        $this->months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        parent::__construct($model);
    }

    public function filterOrders(array $filters = [], array $with = [], array $cols = [], array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->select($cols)
            ->where($conditions)
            ->newQuery();
        return $this->applyFiltersV2($query, $filters);
    }

    public function searchOrderComplete(array $cols, string $query, array $with = []): Collection
    {
        return $this->modal
            ->with($with)
            ->select($cols)
            ->where('code', 'like', '%' . $query . '%')
            ->orWhere('client_name', 'like', '%' . $query . '%')
            ->orWhere('client_phone', 'like', '%' . $query . '%')
            ->get();
    }
    public function filterForUser($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->where('status', '!=', MerchantOrderStatuses::DRAFT->value)
            ->newQuery();

        if (isset($filters['user_id']))
            $query->where('user_id', $filters['user_id']);

        if (isset($filters['status'])) {
            if ($filters['status'] == "past")
                $query->whereIn('status', [MerchantOrderStatuses::CANCELLED->value, MerchantOrderStatuses::PREVIOUS->value, MerchantOrderStatuses::EXPIRED->value]);
            else
                $query->whereNotIn('status', [MerchantOrderStatuses::CANCELLED->value, MerchantOrderStatuses::PREVIOUS->value, MerchantOrderStatuses::EXPIRED->value]);
        }

        return $query;
    }


    public function builder_with_time_range($date, $time, $data)
    {
        return $this->modal->where('merchant_id', $data->merchant_id)
            ->where('merchant_branch_id', $data->merchant_branch_id)
            ->where('date', $date)
            ->where(function ($t1) use ($time) {
                $t1->whereTime('from', '<=', $time);
                //                    ->whereTime('to', '>=', $time);
            });
    }

    public function findWithElmOrder($orderId)
    {
        return $this->modal->find($orderId)->elmOrder();
    }

    public function getMerchantOrderByBranchType(string $type, $user, array $branches, $paginate)
    {
        return $this->modal->where('order_type', $type)
            ->where('merchant_id', $user->merchant_id)
            ->whereIn('merchant_branch_id', $branches)
            ->orderBy('created_at', 'DESC')
            ->cursorPaginate($paginate);
    }

    public function getOrdersByElmStatus(array $filters, $with, $paginate = 20)
    {
        $query = $this->modal->with($with)->newQuery();

        if (isset($filters['status'])) {
            $query->whereIn('status', [$filters['status']]);
        }

        $this->applyElmStatusFilters($query, $filters);
        $this->applyDateConditionFilters($query, $filters);

        $query->with(['elmOrder' => function ($query) {
            $query->select(['id', 'elm_upload_status', 'elm_errors']);
        }]);

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
                $query->whereHas('elmOrder', function ($subQuery) use ($status) {
                    $subQuery->where('elm_upload_status', ElmOrderStatuses::COMPLETED->value)
                        ->whereNull('elm_errors');
                });
                break;
            case 'rejected':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', '!=', ElmOrderStatuses::COMPLETED->value)
                        ->whereNotNull('elm_errors');
                });
                break;
            case 'needs_updating':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', ElmOrderStatuses::NEW_ORDER->value);
                });
                break;
            case 'accepted_by_merchant':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', ElmOrderStatuses::ACCEPTED_BY_MERCHANT->value);
                });
                break;
            case 'driver_assigned':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', ElmOrderStatuses::DRIVER_ASSIGNED->value);
                });
                break;
            case 'executed':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', ElmOrderStatuses::EXECUTED->value);
                });
                break;
            case 'cancelled':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->where('elm_upload_status', ElmOrderStatuses::CANCELLED->value);
                });
                break;
            case 'errors':
                $query->whereHas('elmOrder', function ($query) use ($status) {
                    $query->whereNotNull('elm_errors');
                });
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


    public function getMerchantOrderByBranch($user, array $branches, array $filters, $paginate)
    {
        $query = $this->modal
            ->where('merchant_id', $user->merchant_id)
            ->where('status', '!=', MerchantOrderStatuses::DRAFT->value)
            ->newQuery();

        if (isset($filters['status'])) {
            if ($filters['status'] == "deliveries")
                $query->whereIn('status', [MerchantOrderStatuses::ON_WAY->value, MerchantOrderStatuses::WILL_BE_LATE->value]);

            elseif ($filters['status'] == "new")
                $query->whereIn('status', [MerchantOrderStatuses::PENDING->value]);

            elseif ($filters['status'] == "history")
                $query->whereIn('status', [MerchantOrderStatuses::PREVIOUS->value, MerchantOrderStatuses::CANCELLED->value]);

            elseif ($filters['status'] == "accepted") {
                if (isset($filters['accepted'])) {
                    if ($filters['accepted'] == "confirmed")
                        $query->whereIn('status', [MerchantOrderStatuses::UPCOMING->value]);

                    if ($filters['accepted'] == "started-work")
                        $query->whereIn('status', [MerchantOrderStatuses::CURRENT->value]);

                    if ($filters['accepted'] == "cooking")
                        $query->whereIn('status', [MerchantOrderStatuses::CURRENT->value]);

                    if ($filters['accepted'] == "ready")
                        $query->whereIn('status', [MerchantOrderStatuses::READY->value]);

                    if ($filters['accepted'] == "on-way")
                        $query->whereIn('status', [MerchantOrderStatuses::ON_WAY->value]);
                } else {
                    $query->whereIn('status', [
                        MerchantOrderStatuses::UPCOMING->value,
                        MerchantOrderStatuses::CURRENT->value,
                        MerchantOrderStatuses::READY->value,
                        MerchantOrderStatuses::ON_WAY->value,
                    ]);
                }
            }
        }

        return $query
            ->whereIn('merchant_branch_id', $branches)
            ->orderBy('created_at', 'DESC')
            ->cursorPaginate($paginate);
    }

    public function getMerchantOrderById(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['id', 'total_service', 'user_id', 'status', 'merchant_id', 'created_at'], ['services', 'user'], [], 'desc')->where('merchant_id', $merchant_id);
    }
    public function getAllMerchantOrder(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['*'], ['services', 'user', 'driver', 'elmOrder', 'merchant', 'branch', 'address'], [], 'desc')->where('merchant_id', $merchant_id);
    }

    public function getUserOrderById(int $user_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this
            ->builder(['id', 'total_service', 'user_id', 'status', 'merchant_id', 'created_at'], ['services', 'merchant', 'user'], [], 'desc')
            ->where('user_id', $user_id);
    }

    public function getOrders($status = null): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['id', 'total_service', 'user_id', 'status', 'created_at'], ['services', 'user'], $status ? ['status' => $status] : [], 'desc');
    }

    public function find_employee_order($data)
    {
        return $this->modal::whereHas('branch', function ($q) {
            $q->whereHas('manager', function ($q) {
                $q->where('merchant_employees.id', auth()->id());
            });
        })
            //              ->whereStatus(MerchantOrderStatuses::UPCOMING->value)
            //            ->whereOrderType('mobile')
            ->find($data["order_id"]);
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }

    public function sum(array $filters, $col)
    {
        return $this->modal->where($filters)->sum($col);
    }

    public function getOrdersByMonth(array $filters = [])
    {
        $results = Cache::remember('monthly_counts', 60, function () use ($filters) {
            return $this->modal->where($filters)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();
        });
        $list = [];
        foreach ($this->months as $key => $val) {
            $monthNumber = $key + 1;
            $list[$key]['x'] = $val;
            $list[$key]['y'] = $results[$monthNumber] ?? 0;
        }
        
        return $list;
    }

    public function getSalesByMonth(array $filters = [])
    {
        $results = Cache::remember('monthly_totals', 60, function () use ($filters) {
            return $this->modal->where($filters)
                ->selectRaw('MONTH(created_at) as month, SUM(total_after_discount) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
        });
        $list = [];
        foreach ($this->months as $key => $val) {
            $monthNumber = $key + 1;
            $list[$key]['x'] = $val;
            $list[$key]['y'] = $results[$monthNumber] ?? 0;
        }
        
        return $list;
    }

    public function getAllOrdersAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function GetSettingExpiredOrder()
    {
        try {
            $this->settings = GeneralSetting::select('pending_order_expiry', 'confirmed_order_expiry', 'resend_order_to_drivers')->first();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }

    public function getPastDueTimeOrders()
    {
        $this->GetSettingExpiredOrder();
        return $this->modal
            ->select(['id', 'status'])
            ->whereIn('status', [
                MerchantOrderStatuses::PENDING->value,
                MerchantOrderStatuses::UPCOMING->value,
            ])
            ->where(function ($query) {
                $query->where('status', MerchantOrderStatuses::PENDING->value)
                    ->where('created_at', '<=', Carbon::now()->subMinutes($this->settings?->pending_order_expiry ?? 2)->format('Y-m-d H:i:s'));
            })
            ->orWhere(function ($query) {
                $query->where('status', MerchantOrderStatuses::UPCOMING->value)
                    ->whereDoesntHave('driver')
                    ->where('created_at', '<=', Carbon::now()->subMinutes($this->settings?->confirmed_order_expiry ?? 2)->format('Y-m-d H:i:s'));
            })
            ->get();
    }

    public function getOrdersWithoutDriver()
    {
        $this->GetSettingExpiredOrder();
        return $this->modal
            ->whereIn('status', [MerchantOrderStatuses::UPCOMING->value])
            ->where('service_type_id', 1)
            ->whereNull('driver_id')
            ->where('created_at', '<=', Carbon::now()->subMinutes($this->settings?->resend_order_to_drivers ?? 2)->format('Y-m-d H:i:s'))
            ->get();
    }
    public function getDraftOrdersWithPaymentStatusPaid(): array|Collection
    {
        return $this->modal
            ->where('status', MerchantOrderStatuses::DRAFT->value)
            ->whereHas('payment', function ($query) {
                $query->where('status', 'paid')
                    ->where('updated_at', '<=', Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'))
                    ->where('updated_at', '>=', '2024-08-25 20:00:00');
            })
            ->get();
    }

    public function getPendingOrdersWithPaymentStatusFiald()
    {
        return $this->modal
            ->where('status', MerchantOrderStatuses::PENDING->value)
            ->whereDoesntHave('payment', function ($query) {
                $query->where('status', 'paid')
                    ->where('updated_at', '<=', Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'))
                    ->where('updated_at', '>=', '2024-08-25 20:00:00');
            })
            ->get();
    }

    public function getCompanyDriversOrders(int $company_id, array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->where('status', MerchantOrderStatuses::PREVIOUS->value)
            ->whereHas('driver', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })
            ->newQuery();
        if (isset($filters['driver_id'])) {
            $query->whereHas('driver', function ($q) use ($filters) {
                $q->where('id', $filters['driver_id']);
            });
        }

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


    protected function applyFilters(array $filters = [], $query): \Illuminate\Database\Eloquent\Builder
    {
        return $this->applyFiltersV2($query, $filters);
    }
    protected function applyFiltersV2($query, array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        if (isset($filters['merchant_id']))
            $query->where('merchant_id', $filters['merchant_id']);

        if (isset($filters['user_id']))
            $query->where('user_id', $filters['user_id']);

        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['merchant_branch_id']) && $filters['merchant_branch_id'] != null)
            $query->where('merchant_branch_id', $filters['merchant_branch_id']);

        if (isset($filters['code']) && $filters['code'] != null)
            $query->where('code', $filters['code']);

        if (isset($filters['district_id']) && $filters['district_id'] != null) {
            $query->whereHas('branch', function ($q) use ($filters) {
                $q->where('distract_id', $filters['district_id']);
            });
        }

        if (isset($filters['mark']) && $filters['mark'] != null) {
            $query->where('mark', $filters['mark']);
        }

        if (isset($filters['city_id']) && $filters['city_id'] != null) {
            $query->whereHas('branch', function ($q) use ($filters) {
                $q->where('city_id', $filters['city_id']);
            });
        }

        if (isset($filters['driver_status'])) {
            $query->whereHas('driver', function ($q) use ($filters) {
                $q->where('status', $filters['driver_status']);
            });
        }

        // if (isset($filters['pending']))
        // {
        //     $query->where('status', $filters['pending']);
        // }
        // if (isset($filters['confirmed']))
        // {
        //     $query->where('status', $filters['confirmed']);
        // }
        if (isset($filters['statusList'])) {
            $query->whereIn('status', $filters['statusList']);
        }

        if (isset($filters['driver_id'])) {
            $query->whereHas('driver', function ($q) use ($filters) {
                $q->where('id', $filters['driver_id']);
            });
        }
        if (isset($filters['network_status'])) {
            if ($filters['network_status'] == "online") {
                $query->whereHas('driver', function ($q) {
                    $q->where('network_status', 1);
                });
            } elseif ($filters['network_status'] == "offline") {
                $query->whereHas('driver', function ($q) {
                    $q->where('network_status', 0);
                });
            }
        }

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
}
