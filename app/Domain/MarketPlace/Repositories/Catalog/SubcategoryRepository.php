<?php

namespace App\Domain\MarketPlace\Repositories\Catalog;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Catalog\Subcategory;

class SubcategoryRepository extends EloquentMainRepository
{
    public function __construct(Subcategory $model)
    {
        parent::__construct($model);
    }

    public function getUsedByMerchant(int $merchantId, bool $allowDuplicates = true)
    {
        $query = $this->modal::query()
            ->with([
                'category:id,title',
                'childCategories:id,subcategory_id,title',
            ])
            ->whereHas('items', fn($q) => $q->where('merchant_id', $merchantId))
            ->withCount([
                'items as items_count' => fn($q) => $q->where('merchant_id', $merchantId)
            ])
            ->select('id', 'category_id', 'title', 'slug', 'description', 'is_active')
            ->orderBy('title');

        if (!$allowDuplicates) {
            $query->distinct();
        }

        return $query->get();
    }


}
