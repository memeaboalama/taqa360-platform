<?php

namespace App\Application\Ecommerce\Support;

use App\Domain\Catalog\Models\SpecificationProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MerchantSpecificationFilters
{
    public function facets(Builder $products, ?int $fieldId = null): Collection
    {
        // Read current template settings, including changes made after products were saved.
        $masters = SpecificationProperty::query()->whereNull('item_id')->where('status', 'active')
            ->when($fieldId !== null, fn (Builder $query) => $query->whereKey($fieldId))
            ->where('meta->filterable', true)
            ->whereHas('specification', fn (Builder $query) => $query->whereNull('item_id')->where('status', 'active'))
            ->orderBy('sort_order')->orderBy('id')->get()->keyBy('id');
        if ($masters->isEmpty()) {
            return collect();
        }

        $properties = SpecificationProperty::query()->where('status', 'active')
            ->whereIn('item_id', (clone $products)->reorder()->select('items.id'))
            ->whereIn('meta->master_property_id', $masters->modelKeys())
            ->whereHas('specification', fn (Builder $query) => $query->where('status', 'active'))
            ->get()->groupBy('master_property_id');

        return $masters->map(function ($master) use ($properties) {
            $type = $master->meta['input_type'] ?? 'short_text';
            if ($type === 'attachment') {
                return null;
            }
            $options = [];
            foreach ($properties->get($master->id, collect()) as $property) {
                $values = $type === 'multiselect' ? json_decode((string) $property->value, true) : [$property->value];
                foreach (is_array($values) ? $values : [] as $value) {
                    if (!is_scalar($value) || !filled($value)) {
                        continue;
                    }
                    $value = (string) $value;
                    $key = 'value:' . $value;
                    $display = new SpecificationProperty(['name' => $master->name, 'value' => $value, 'meta' => $master->meta]);
                    // A single option of a multi-select is plain text, not an encoded array.
                    if ($type === 'multiselect') {
                        $display->value = json_encode([$value], JSON_UNESCAPED_UNICODE);
                    }
                    $options[$key] ??= ['value' => $value, 'label' => $display->display_value, 'item_ids' => []];
                    $options[$key]['item_ids'][(int) $property->item_id] = (int) $property->item_id;
                }
            }
            if (!$options) {
                return null;
            }

            return ['id' => $master->id, 'label' => $master->display_name,
                'options' => collect($options)->sortBy('label', SORT_NATURAL)->values()->all()];
        })->filter();
    }

    public function selection(Collection $facets, $input): array
    {
        if (!is_array($input)) {
            return [];
        }
        $selected = [];
        foreach ($facets as $facet) {
            $values = $input[$facet['id']] ?? [];
            if (!is_array($values)) {
                continue;
            }
            $allowed = array_column($facet['options'], 'value');
            $values = array_values(array_unique(array_filter($values, fn ($value) => is_string($value) && in_array($value, $allowed, true))));
            if ($values) {
                $selected[$facet['id']] = $values;
            }
        }
        return $selected;
    }

    public function optionPage(Collection $facets, int $fieldId, string $search, int $page): array
    {
        $search = mb_strtolower(trim($search));
        $options = collect($facets->get($fieldId)['options'] ?? [])
            ->filter(fn ($option) => $search === '' || str_contains(mb_strtolower($option['label'] . ' ' . $option['value']), $search))
            ->values();
        $offset = (max(1, $page) - 1) * 30;

        return [
            'results' => $options->slice($offset, 30)->map(fn ($option) => [
                'value' => $option['value'], 'label' => $option['label'], 'count' => count($option['item_ids']),
            ])->values()->all(),
            'has_more' => $options->count() > $offset + 30,
        ];
    }

    public function matchingItemIds(Collection $facets, array $selected): ?array
    {
        $matches = null;
        foreach ($facets as $facet) {
            if (empty($selected[$facet['id']])) {
                continue;
            }
            $ids = [];
            foreach ($facet['options'] as $option) {
                if (in_array($option['value'], $selected[$facet['id']], true)) {
                    $ids = array_merge($ids, array_values($option['item_ids']));
                }
            }
            // Any selected value within a field, all selected fields across the form.
            $matches = $matches === null ? array_unique($ids) : array_intersect($matches, $ids);
        }
        return $matches === null ? null : array_values($matches);
    }
}
