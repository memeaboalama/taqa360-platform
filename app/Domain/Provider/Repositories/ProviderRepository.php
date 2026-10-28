<?php

namespace Domain\Provider\Repositories;

use App\Domain\Provider\Models\Provider;
use App\Domain\Provider\Models\ProviderOrder;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\OrderActionEnum;
use App\Support\Enum\StatusEnum;
use App\Support\Enum\VehicleType;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ProviderRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Provider $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Point $location
     * @param $distance
     * @param $order
     * @return mixed
     */
    public function get_nearest_to(Point $location , $distance, $order): mixed
    {
        return $this->modal::select('id', 'company_id', 'nationality_id', 'name', 'phone', 'dialcode', 'country_code', 'location', 'network_status', 'status')
            ->where(['network_status' => NetworkStatusEnum::ONLINE, 'status' => StatusEnum::ACTIVE])
            ->withCount(['order_actions' => fn($q) => $q->where('provider_order_id', $order?->id)->where('action', OrderActionEnum::REJECTED_BY_PROVIDER)])
            ->withCount(['vehicles' => fn($q) => $q->where('is_towing', StatusEnum::ACTIVE)])
            ->withCount(['provider_orders' => fn($q) => $q->where('status', '!=', 'completed')])
            ->having('provider_orders_count', 0)
            ->having('order_actions_count', 0)
            ->whereDistance('location', $location, '<=', $distance)
            ->get();
    }

    /**
     * @param int $provider_id
     * @param ProviderOrder $order
     * @param OrderActionEnum $orderAction
     * @return Model|null
     */
    public function insert_provider_order_action(int $provider_id , ProviderOrder $order, OrderActionEnum $orderAction): ?Model
    {
        $provider = $this->find(['id'], ['id' => $provider_id]);
        $provider->order_actions()->create([
            'provider_order_id' => $order?->id,
            'action' => $orderAction,
        ]);

        return $provider;
    }
    public function getByCompanyId(int $company_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(['*'], [], ['company_id' => $company_id], 'desc');
    }

    public function fetchProviderCurrentOrder($provider)
    {
        return $provider->orders()->where('status','!=',OrderActionEnum::COMPLETED)->first();
    }

    public function getAllProvidersAsBuilder(array $cols = [], array $relations = [], $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $relations, $conditions, 'desc');
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }

}
