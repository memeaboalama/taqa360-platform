<?php

namespace App\Domain\Ecommerce\Repositories\Cart;

use App\Domain\Ecommerce\Models\WebCart;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class WebCartRepository extends EloquentMainRepository
{
    public function __construct(WebCart $model)
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
