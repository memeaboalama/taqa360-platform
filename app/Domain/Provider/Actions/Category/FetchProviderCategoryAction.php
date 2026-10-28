<?php

namespace App\Domain\Provider\Actions\Category;

use App\Domain\Provider\Repositories\ProviderCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchProviderCategoryAction
{


    private ProviderCategoryRepository $providerCategoryRepository;

    public function __construct(ProviderCategoryRepository $providerCategoryRepository)
    {
        $this->providerCategoryRepository = $providerCategoryRepository;
    }

    /**
     * @param array $data
     * @param int $mainCategory
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = [] , int $main_category_id): LengthAwarePaginator|Collection
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->providerCategoryRepository->paginate(cols: ['id','title','main_category_id'], relations: ['main_category:id,title'],condition: ['main_category_id'=>$main_category_id], paginate: $per_page);
        }
        return $this->providerCategoryRepository->all(['id','title','main_category_id'], relations: ['main_category:id,title'], condition: ['main_category_id'=>$main_category_id]);
    }
}
