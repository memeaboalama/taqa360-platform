<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;

class UserRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $cols
     * @param array $with
     * @param array $select
     * @return Model|null
     */
    public function findByCols(array $cols,array $with = [],array $select = ['*']): ?Model
    {
        return $this->modal::where($cols)->select($select)->first();
    }

    public function auth_user(): Authenticatable|User|null
    {
        return auth()->user();
    }

    /**
     * @param User|Authenticatable|null $user
     * @param array $condition
     * @param array $payload
     * @return bool|int|null
     */
    public function update_phone_data(User|Authenticatable|null $user, array $condition = [], array $payload): bool|int|null
    {
        return $user->phones()->where($condition)->first()?->update($payload);
    }

    /**
     * @param User|Authenticatable|null $user
     * @param array $condition
     * @return Model|null
     */
    public function fetch_phone_data(User|Authenticatable|null $user, array $condition = []) : Model|null
    {
        return $user->phones()->where($condition)->first();
    }

    /**
     * @param User|Authenticatable|null $user
     * @return mixed
     */
    public function fetch_user_addresses(User|Authenticatable|null $user): mixed
    {
        return $user->addresses;
    }

    /**
     * @param User|Authenticatable|null $user
     * @param int $per_page
     * @return mixed
     */
    public function fetch_user_addresses_paginated(User|Authenticatable|null $user , int|null $per_page = 10): mixed
    {
        return $user->addresses()->with('city','distract')->paginate($per_page);
    }

    /**
     * @param User|Authenticatable|null $user
     * @param array $data
     * @return mixed
     */
    public function store_user_addresses(User|Authenticatable|null $user , array $data = []): mixed
    {
        if ($data['is_default'])
        {
            $user->addresses()->update(['is_default' => 0]);
        }
        return $user->addresses()->with('city','distract')->create($data);
    }

    /**
     * @param User $user
     * @param array $data
     * @param int $address_id
     * @return int
     */
    public function update_user_addresses(User $user, array $data, int $address_id): mixed
    {
        $address = $user->addresses()->find($address_id);
        if(!$address) return  false;
        if ($data['is_default'])
        {
            $user->addresses()->where('id','!=', $address->id)->update(['is_default' => 0]);
        }
        return tap($user->addresses()->find($address_id))?->update($data);

    }

    /**
     * @param User $user
     * @param int $address_id
     * @return int|null
     */
    public function delete_user_addresses(User $user, int $address_id): ?int
    {
        return $user->addresses()->find($address_id)?->delete();

    }

    /**
     * @param User $user
     * @return mixed
     */
    public function fetch_user_settings(User $user): mixed
    {
        return $user->userSetting;
    }

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function update_user_settings(User $user, array $data): mixed
    {
        return tap($user->userSetting)->update($data);

    }

    public function fetch_user_cars_paginated(User $user, int|null $per_page): LengthAwarePaginator
    {
        return $user->cars()->with('brand','model')->paginate($per_page);

    }

    public function fetch_user_cars(User $user): Collection
    {
        return $user->cars()->with('brand','model')->get();

    }

    public function store_user_cars(User $user, array $data)
    {
        return $user->cars()->with('brand','model')->create($data);
    }

    public function update_user_cars(User $user, array $data,int $car_id)
    {
        return tap($user->cars()->with('brand','model')->find($car_id))?->update($data);
    }

    public function delete_user_cars(User $user,int $car_id): int
    {
        return $user->cars()->find($car_id)->delete();
    }
    public function getAllUsersAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function search(array $cols, string $query) : Collection
    {
        return $this->modal->select($cols)
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->get();
    }

    public function filter($filters = [], array $with = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->modal
            ->with($with)
            ->newQuery();

        if (isset($filters['status']))
            $query->where('status', $filters['status']);

        if (isset($filters['customer_id']))
            $query->where('id', $filters['customer_id']);

        return $query;
    }
}
