<?php

namespace App\Domain\Ecommerce\Actions\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FetchMerchantItemAction
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle(array $filters = [], int $merchant_id): Collection|LengthAwarePaginator
    {
        return $this->itemRepository->getFilteredItems($filters, $merchant_id);
    }

    public function getCategoryCounts(int $merchant_id, $categories): array
    {
        return $this->itemRepository->getCategoryCounts($merchant_id, $categories);
    }

    public function specificationFacets(array $filters, int $merchantId): Collection
    {
        return app(\App\Application\Ecommerce\Support\MerchantSpecificationFilters::class)
            ->facets($this->itemRepository->filterMerchantItems($filters, $merchantId));
    }
}
