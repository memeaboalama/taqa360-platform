<?php

namespace App\Domain\MarketPlace\Repositories\Cart;

use App\Domain\AppBase\Models\City;
use App\Domain\MarketPlace\Models\Cart;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Illuminate\Database\Eloquent\Model;

class CartRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreate(array $updated, array $payload): Model
    {
        return $this->modal::updateOrCreate($updated, $payload);

    }

    public function fetch(array $cols = ['*'], array $relations = [], array $conditions = [])
    {
        return $this->modal::select($cols)->with($relations)->where($conditions)->first();
    }

    public function deleteItem($id): bool
    {
        $data = request()->user()->cart->cart_services()?->find($id);
        if ($data) {
            $data->addons()->delete();
            $data->delete();
        }
        if (!request()->user()?->cart?->cart_services()->exists() &&
            count(request()->user()?->cart?->cart_services) == 0) request()->user()?->cart()->delete();
        return true;
    }
}
