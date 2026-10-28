<?php

namespace App\Contracts\DB;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * Get model builder.
     *
     * @param array $cols
     * @param array $relations
     * @param array $condition
     * @param string $order
     * @param string $orderCol
     * @return Builder
     */
    public function builder(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc',$orderCol = 'id'): Builder;


    /**
     * Get model data paginate.
     *
     * @param array $cols
     * @param array $relations
     * @param array $condition
     * @param string $order
     * @param string $orderCol
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function paginate(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc',string $orderCol = 'id', int $paginate = 10): LengthAwarePaginator;


    /**
     * Get model all data.
     *
     * @param array $cols
     * @param array $relations
     * @param array $condition
     * @param string $order
     * @param string $orderCol
     * @return Collection
     */
    public function all(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc',string $orderCol = 'id'): Collection;


    /**
     * store data model.
     *
     * @param array $data
     * @return Model|null
     */
    public function store(array $data): ?Model;


    /**
     * update model with id.
     *
     * @param array $data
     * @param int $id
     */
    public function update(int $id, array $data): ?Model;


    /**
     * find model with columns.
     *
     * @param array $cols
     */
    public function findByCols(array $cols): ?Model;


    /**
     * Destroy model with id.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;


}
