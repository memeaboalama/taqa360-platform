<?php

namespace App\Domain\Ecommerce\Actions;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchProductAction
{
    protected ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle($data = []): CursorPaginator|Collection
    {
        try {
            $query = $this->itemRepository->getQuery();

            $this->applyBaseFilters($query);

            $this->applyCustomFilters($query, $data);

            $this->applySorting($query, $data);

            $this->loadRelationships($query, $data);

            return $this->executeQuery($query, $data);

        } catch (\Exception $e) {
            Log::error('FetchItemsAction error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByCategory(int $category_id, $data = []): CursorPaginator|Collection
    {
        $data['category_id'] = $category_id;
        return $this->handle($data);
    }

    public function getSingleProduct(int $productId)
    {
        $query = $this->itemRepository->getQuery();

        $this->applyBaseFilters($query);
        $this->loadAllRelationships($query);

        return $query->where('id', $productId)->first();
    }

    public function searchProducts(string $searchTerm, array $filters = [])
    {
        $filters['search'] = $searchTerm;
        return $this->handle($filters);
    }

    public function getFeaturedProducts($limit = 8)
    {
        return $this->handle([
            'paginated' => false,
            'per_page' => $limit,
            'featured' => true,
            'sort_by' => 'featured'
        ]);
    }

    public function getNewArrivals($limit = 6)
    {
        return $this->handle([
            'paginated' => false,
            'per_page' => $limit,
            'new' => true,
            'sort_by' => 'latest'
        ]);
    }

    public function getBestSellers($limit = 6)
    {
        return $this->handle([
            'paginated' => false,
            'per_page' => $limit,
            'best_sellers' => true,
            'sort_by' => 'popular'
        ]);
    }

    /**
     * ========== الدوال المساعدة ==========
     */

    /**
     * تطبيق الفلاتر الأساسية
     */
    private function applyBaseFilters(Builder $query): void
    {
        $query->where("status", StatusEnum::ACTIVE->value);
    }

    /**
     * تطبيق الفلاتر المخصصة
     */
    private function applyCustomFilters(Builder $query, array $data): void
    {
        // البحث في العنوان والوصف
        if (isset($data['search']) && !empty($data['search'])) {
            $this->applySearchFilter($query, $data['search']);
        }

        // التصنيف
        if (isset($data['category_id'])) {
            $query->where('category_id', $data['category_id']);
        }

        // نطاق السعر
        if (isset($data['price_range'])) {
            $this->applyPriceFilter($query, $data['price_range']);
        }

        // المنتجات المميزة
        if (isset($data['featured']) && $data['featured']) {
            $query->where('is_featured', true);
        }

        // المنتجات الجديدة
        if (isset($data['new']) && $data['new']) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // الأكثر مبيعاً
        if (isset($data['best_sellers']) && $data['best_sellers']) {
            $query->orderBy('sales_count', 'desc');
        }

        // المنتجات الحصرية
        if (isset($data['exclusive']) && $data['exclusive']) {
            $query->where('is_exclusive', true);
        }

        // منتج واحد
        if (isset($data['single'])) {
            $query->where('id', $data['single']);
        }
    }

    /**
     * تطبيق فلترة البحث
     */
    private function applySearchFilter(Builder $query, string $searchTerm): void
    {
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%")
              ->orWhere('tags', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                  $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    /**
     * تطبيق فلترة السعر
     */
    private function applyPriceFilter(Builder $query, string $priceRange): void
    {
        switch ($priceRange) {
            case 'under_50':
                $query->where('default_price', '<', 50);
                break;
            case '50_100':
                $query->whereBetween('default_price', [50, 100]);
                break;
            case '100_200':
                $query->whereBetween('default_price', [100, 200]);
                break;
            case '200_500':
                $query->whereBetween('default_price', [200, 500]);
                break;
            case 'over_500':
                $query->where('default_price', '>', 500);
                break;
        }
    }

    private function applySorting(Builder $query, array $data): void
    {
        $sortBy = $data['sort_by'] ?? 'latest';

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('default_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('default_price', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc')
                      ->orderBy('sales_count', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $query->orderBy('id', 'desc');
    }


    private function loadRelationships(Builder $query, array $data): void
    {
        $relations = ['category'];

        if (isset($data['with_images']) && $data['with_images']) {
            $relations[] = 'images';
        }

        if (isset($data['with_addition_groups']) && $data['with_addition_groups']) {
            $relations[] = 'additionGroups';
            $relations[] = 'additionGroups.choices';
        }

        $query->with($relations);
    }


    private function loadAllRelationships(Builder $query): void
    {
        $query->with([
            'category',
            'images',
            'additionGroups',
            'additionGroups.choices',
            'reviews',
            'reviews.user'
        ]);
    }

    private function executeQuery(Builder $query, array $data): CursorPaginator|Collection
    {
        $columns = ['id', 'title', 'description', 'default_price', 'calories', 'preparing_time', 'image', 'status', 'category_id', 'is_featured', 'is_exclusive', 'sales_count', 'views_count', 'created_at'];

        if (isset($data['paginated'])) {
            $perPage = $data['per_page'] ?? 12;
            return $query->cursorPaginate($perPage, $columns);
        }

        $limit = $data['per_page'] ?? 6;
        return $query->limit($limit)->get($columns);
    }


    public function getProductStats(): array
    {
        return [
            'total_products' => $this->itemRepository->getQuery()->where('status', StatusEnum::ACTIVE->value)->count(),
            'featured_products' => $this->itemRepository->getQuery()->where('status', StatusEnum::ACTIVE->value)->where('is_featured', true)->count(),
            'new_products' => $this->itemRepository->getQuery()->where('status', StatusEnum::ACTIVE->value)->where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }
}
