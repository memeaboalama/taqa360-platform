<?php

namespace App\Domain\AppBase\Actions\Product;

use App\Support\Enum\FilterConditionEnum;
use App\Support\Enum\ProductQueryPresetEnum;
use App\Support\Enum\ProductQueryTypeEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ExecuteProductQueryAction
{
    public function handle(array $queryConfig, int $limit = 10): Collection
    {
        $queryType = $queryConfig['type'] ?? ProductQueryTypeEnum::PRESET->value;

        return match($queryType) {
            ProductQueryTypeEnum::PRESET->value => $this->executePresetQuery($queryConfig, $limit),
            ProductQueryTypeEnum::CUSTOM->value => $this->executeCustomQuery($queryConfig, $limit),
            ProductQueryTypeEnum::EXPERT->value => $this->executeExpertQuery($queryConfig, $limit),
            default => $this->executeFallbackQuery($limit),
        };
    }

    protected function executePresetQuery(array $config, int $limit): Collection
    {
        $preset = $config['preset'] ?? ProductQueryPresetEnum::LATEST->value;
        $presetConfig = $config['config'] ?? [];

        $query = Item::query()->where('status', \App\Support\Enum\ActiveEnum::ACTIVE);

        switch ($preset) {
            case ProductQueryPresetEnum::LATEST->value:
                $days = $presetConfig['days'] ?? 30;
                $query->where('created_at', '>=', now()->subDays($days))->orderBy('created_at', 'desc');
                break;

            case ProductQueryPresetEnum::BEST_SELLING->value:
                $minSales = $presetConfig['min_sales'] ?? 1;
                $period = $presetConfig['period'] ?? 'all_time';

                $query->orderBy('created_at', 'desc');
                break;

            case ProductQueryPresetEnum::FEATURED->value:
                $query->orderBy('created_at', 'desc');
                break;

            case ProductQueryPresetEnum::ON_SALE->value:
                $minDiscount = $presetConfig['min_discount'] ?? 1;
                $query->orderBy('created_at', 'desc');
                break;

            case ProductQueryPresetEnum::PRICE_LOW_HIGH->value:
                $query->orderBy('default_price', 'asc');
                break;

            case ProductQueryPresetEnum::PRICE_HIGH_LOW->value:
                $query->orderBy('default_price', 'desc');
                break;

            case ProductQueryPresetEnum::MOST_VIEWED->value:
                $query->orderBy('created_at', 'desc');
                break;

            case ProductQueryPresetEnum::TRENDING->value:
                $query->where('created_at', '>=', now()->subDays(7))->orderBy('created_at', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query->limit($limit)->get();
    }

    protected function executeCustomQuery(array $config, int $limit): Collection
    {
        $query = Item::query()->where('status', \App\Support\Enum\ActiveEnum::ACTIVE);

        if (!empty($config['filters'])) {
            foreach ($config['filters'] as $filter) {
                $this->applyFilter($query, $filter);
            }
        }

        if (!empty($config['sort'])) {
            foreach ($config['sort'] as $sort) {
                $query->orderBy($sort['field'], $sort['order'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->limit($limit)->get();
    }

    protected function executeExpertQuery(array $config, int $limit): Collection
    {
        $query = Item::query()->where('status', \App\Support\Enum\ActiveEnum::ACTIVE);

        if (!empty($config['groups'])) {
            $query->where(function($q) use ($config) {
                foreach ($config['groups'] as $groupIndex => $group) {
                    $logic = $group['logic'] ?? 'AND';

                    $method = ($groupIndex === 0 || $logic === 'AND') ? 'where' : 'orWhere';

                    $q->$method(function($subQuery) use ($group) {
                        foreach ($group['filters'] as $filter) {
                            $this->applyFilter($subQuery, $filter);
                        }
                    });
                }
            });
        }

        if (!empty($config['sort'])) {
            foreach ($config['sort'] as $sort) {
                $query->orderBy($sort['field'], $sort['order'] ?? 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->limit($limit)->get();
    }

    protected function applyFilter(Builder $query, array $filter): void
    {
        $field = $filter['field'];
        $condition = $filter['condition'];
        $value = $filter['value'] ?? null;

        switch ($condition) {
            case FilterConditionEnum::EQUALS->value:
                $query->where($field, '=', $value);
                break;

            case FilterConditionEnum::NOT_EQUALS->value:
                $query->where($field, '!=', $value);
                break;

            case FilterConditionEnum::GREATER_THAN->value:
                $query->where($field, '>', $value);
                break;

            case FilterConditionEnum::GREATER_THAN_OR_EQUAL->value:
                $query->where($field, '>=', $value);
                break;

            case FilterConditionEnum::LESS_THAN->value:
                $query->where($field, '<', $value);
                break;

            case FilterConditionEnum::LESS_THAN_OR_EQUAL->value:
                $query->where($field, '<=', $value);
                break;

            case FilterConditionEnum::BETWEEN->value:
                $values = is_array($value) ? $value : [$value, $filter['value2'] ?? $value];
                $query->whereBetween($field, $values);
                break;

            case FilterConditionEnum::IN->value:
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereIn($field, $values);
                break;

            case FilterConditionEnum::NOT_IN->value:
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereNotIn($field, $values);
                break;

            case FilterConditionEnum::CONTAINS->value:
                $query->where($field, 'LIKE', "%{$value}%");
                break;

            case FilterConditionEnum::STARTS_WITH->value:
                $query->where($field, 'LIKE', "{$value}%");
                break;

            case FilterConditionEnum::ENDS_WITH->value:
                $query->where($field, 'LIKE', "%{$value}");
                break;

            case FilterConditionEnum::LAST_N_DAYS->value:
                $query->where($field, '>=', now()->subDays((int)$value));
                break;

            case FilterConditionEnum::NEXT_N_DAYS->value:
                $query->where($field, '<=', now()->addDays((int)$value));
                break;
        }
    }

    protected function executeFallbackQuery(int $limit): Collection
    {
        return Item::query()
            ->where('status', \App\Support\Enum\ActiveEnum::ACTIVE)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
