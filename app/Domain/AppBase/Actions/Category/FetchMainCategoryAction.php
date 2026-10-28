<?php

namespace App\Domain\AppBase\Actions\Category;

use App\Domain\AppBase\Repositories\MainCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchMainCategoryAction
{

    private MainCategoryRepository $mainCategoryRepository;

    public function __construct(MainCategoryRepository $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->mainCategoryRepository->paginate(cols: ['id','title'], paginate: $per_page);
        }
        return $this->mainCategoryRepository->all(['id','title']);
    }
}
