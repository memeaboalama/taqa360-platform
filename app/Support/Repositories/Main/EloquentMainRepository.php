<?php

namespace App\Support\Repositories\Main;

use App\Contracts\DB\EloquentRepositoryInterface;
use Faker\Core\Uuid;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentMainRepository implements EloquentRepositoryInterface
{
    protected Model $modal;

    public function __construct(Model $model)
    {
        $this->modal = $model;
    }

    public function builder(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc',$orderCol = 'id'): Builder
    {
        return $this->modal::with($relations)->where($condition)->select($cols)->orderBy($orderCol , $order);
    }

    public function paginate(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc', string $orderCol = 'id', int|null $paginate = 10): LengthAwarePaginator
    {
        return $this->builder($cols, $relations,$condition, $order, $orderCol)->paginate($paginate ?? 10);
    }

    public function cursorPaginate(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc' , string $orderCol = 'id', int|null $paginate = 10 ): CursorPaginator
    {
        return $this->builder($cols, $relations,$condition, $order , $orderCol)->cursorPaginate($paginate ?? 10);
    }

    public function all(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc', string $orderCol = 'id'): Collection
    {
        return $this->builder($cols, $relations,$condition, $order,$orderCol)->get();
    }

    public function store(array $data): ?Model
    {
        return $this->modal::create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        $team = $this->findByCols(['id' => $id]);
        $team->update($data);
        return $team;
    }

    public function updateWithReturn(int $id, array $data): ?Model
    {
        $team = $this->findByCols(['id' => $id]);
        return tap($team)->update($data);

    }

    public function findByCols(array $cols,array $with = [],array $select = ['*']): ?Model
    {
        return $this->modal::with($with)->select($select)->where($cols)->first();
    }

    public function find(array $cols , array $conditions): ?Model
    {
        return $this->modal::select($cols)->where($conditions)->first();
    }

    public function findById(int $id): ?Model
    {
        return $this->findByCols(['id' => $id]);
    }

    public function findByUuid(Uuid $uuid): ?Model
    {
        return $this->findByCols(['uuid', $uuid]);
    }

    public function destroy(int $id): bool
    {
        return $this->modal::find($id)->delete();
    }

    public function forceDelete(int $id): bool
    {
        return $this->modal::withTrashed()->find($id)->forceDelete();
    }


}
