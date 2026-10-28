<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;
use App\Support\Enum\StatusEnum;

class FetchCategoryAction
{
    protected CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(array $data = [])
    {
        if(isset($data['paginated']) && $data['paginated'] == true)
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->categoryRepository->paginate(cols: ['id','title', 'image'], paginate: $per_page, condition: ['is_active' => 1]);
        }

        return $this->categoryRepository->all(condition: ['is_active' => 1]);
    }


}
