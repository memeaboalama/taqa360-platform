<?php

namespace App\Domain\AppBase\Actions\Transportation\Car;

use App\Domain\AppBase\Repositories\BrandRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchBrandsAction
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        $relations = isset($data['with']) ? [$data['with'] . ":id,title,brand_id"] : [];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->brandRepository->paginate(cols: ['id','title'], relations: $relations, paginate: $per_page);
        }
        return $this->brandRepository->all(cols: ['id','title'],relations:$relations);
    }
}
