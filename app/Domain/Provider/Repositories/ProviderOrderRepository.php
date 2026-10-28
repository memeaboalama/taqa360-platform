<?php

namespace App\Domain\Provider\Repositories;

use App\Domain\Provider\Models\OrderAction;
use App\Domain\Provider\Models\ProviderOrder;
use App\Support\Enum\MainCategoryType;
use App\Support\Enum\OrderActionEnum;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderOrderRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public array $months;

    public function __construct(ProviderOrder $model)
    {
        $this->months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        parent::__construct($model);
    }

    public function update_order_information_with_user($condition = [], $payload = [])
    {
        return tap($this->modal::where($condition)->first())->update($payload);
    }

    public function storeOrderDetails(array $payload, Model $providerOrder)
    {
        return $providerOrder->provider_towing_order_detail()->create($payload);
    }

    public function updateOrderDetails(array $payload, Model $providerOrder)
    {
        return tap($providerOrder->provider_towing_order_detail)->update($payload);
    }

    public function find_order_action(ProviderOrder $order, int $provider_id)
    {
        return $order->order_actions()->where('provider_id', $provider_id)->latest('id')->first();
    }

    public function store_order_action(ProviderOrder $order, int|null $provider_id, OrderActionEnum $action, int|null $admin_id = null, $reason = null): Model
    {
        return $order->order_actions()?->create(['provider_id' => $provider_id, 'admin_id' => $admin_id, 'reason' => $reason, 'action' => $action]);
    }

    public function update_order_action(ProviderOrder $order, int $provider_id, OrderActionEnum $action): ?int
    {
        return $order->order_actions()->where('provider_id', $provider_id)?->update(['action' => $action]);
    }

    public function update_order_action_to_timeout(ProviderOrder $order, int $provider_id): int
    {
        return $order->order_actions()->where('provider_id', '!=', $provider_id)->where('action', OrderActionEnum::PENDING)->update(['action' => OrderActionEnum::TIMEOUT]);
    }

    public function getCompanyOrderById(int $company_id): Builder
    {
        return $this->builder(['client_name', 'provider_name', 'company_id', 'provider_vehicle_plate_number', 'total_order', 'date', 'from', 'to', 'status'], [], ['company_id' => $company_id], 'desc');
    }

    public function getUserOrderById(int $user_id): Builder
    {
        return $this->builder(['client_name', 'provider_name', 'company_id', 'provider_vehicle_plate_number', 'total_order', 'date', 'from', 'to', 'status', 'user_id', 'company_name'], [],
            ['user_id' => $user_id], 'desc');
    }

    public function getProviderOrdersById(int $provider_id, $relations = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['*'], $relations, ['provider_id' => $provider_id], 'desc');
    }


    public function getOrders($status = null, array $relations = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['client_name', 'provider_name', 'company_id', 'provider_vehicle_plate_number', 'total_order', 'date', 'from', 'to', 'status', 'id'], $relations, $status ? ['status' => $status] : [], 'desc');
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();

    }

    public function getOrdersByMonth(array $filters)
    {
        $list = [];
        foreach ($this->months as $key => $val) {
            $new_key = $key + 1;
            $list[$key]['x'] = $val;
            $list[$key]['y'] = $this->modal->where($filters)->whereMonth('created_at', $new_key)->count();
        }

        return $list;
    }

    public function getSalesByMonth(array $filters)
    {
        $list = [];
        foreach ($this->months as $key => $val) {
            $new_key = $key + 1;
            $list[$key]['x'] = $val;
            $list[$key]['y'] = $this->modal->where($filters)->whereMonth('created_at', $new_key)->sum('total_order');
        }

        return $list;
    }

    public function get_current_active_provider_order(int $provider_id)
    {
        return $this->modal::where('status', '!=', OrderActionEnum::COMPLETED)->where('provider_id', $provider_id)->latest()->first();
    }

    public function get_current_active_user_order(int $user_id, MainCategoryType $type = MainCategoryType::TOWING)
    {
        return $this->modal::with('provider')->whereNotIn('status', [OrderActionEnum::COMPLETED, OrderActionEnum::REJECTED_BY_PROVIDER, OrderActionEnum::CANCELLED_BY_CLIENT])->where('provider_vehicle_type', $type->value)->where('user_id', $user_id)->latest()->first();
    }

    public function getAllOrdersAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function updateProviderOrder(int $id, ?Model $provider): ?Model
    {
        return $this->update($id, [
            'provider_id' => $provider?->id,
            'provider_name' => $provider?->name
        ]);
    }

}
