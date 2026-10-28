<?php

namespace App\Domain\MarketPlace\Actions\Api\Category;

use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCategoryAction
{
    private CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): Collection|LengthAwarePaginator
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->categoryRepository->paginate(cols: ['id','title', 'sort','image','color'], paginate: $per_page, condition: ['status' => StatusEnum::ACTIVE]);
        }
    }
}
