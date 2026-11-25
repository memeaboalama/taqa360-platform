<?php

namespace App\Application\Ecommerce\Support;

use Domain\MarketPlace\Models\Item;

class ProductPagePresentation
{
    public static function make(Item $product): array
    {
        $product->loadMissing('catalogSpecifications.properties.masterProperty');
        $fields = $product->catalogSpecifications->flatMap(fn ($specification) => $specification->properties)
            ->filter(fn ($field) => filled($field->value));
        $specifications = $fields->reject(fn ($field) => ($field->meta['input_type'] ?? '') === 'attachment');
        $groups = $specifications->groupBy(fn ($field) =>
            filled($field->meta['group'] ?? null) ? $field->meta['group'] : __('ecommerce::product_page.general_specifications')
        );
        $documents = collect();
        foreach ($product->files->where('collection_name', 'item_specification') as $file) {
            if (self::safeUrl($file->url)) {
                $documents->push(['title' => __('ecommerce::product_page.specification_document'),
                    'name' => $file->original_name ?: __('ecommerce::product_page.view_document'), 'url' => $file->url]);
            }
        }
        foreach ($fields->where('meta.input_type', 'attachment') as $field) {
            if (self::safeUrl($field->value)) {
                $documents->push(['title' => $field->display_name, 'name' => $field->display_name, 'url' => $field->value]);
            }
        }
        $images = collect([$product->image_path])->merge(
            $product->files->where('collection_name', 'item_additional_image')->pluck('url')
        )->filter(fn ($url) => self::safeUrl($url))->unique()->values();
        if ($images->isEmpty()) {
            $images->push(asset('assets/ecommerce/images/product-placeholder.svg'));
        }

        return [
            'images' => $images,
            'groups' => $groups,
            'documents' => $documents->unique('url')->values(),
            'highlights' => $specifications->filter(function ($field) {
                // Product copies keep their values; summary visibility follows the current template.
                $meta = $field->masterProperty?->meta ?? $field->meta;

                return !empty($meta['show_in_summary']);
            })->take(4),
        ];
    }

    private static function safeUrl($url): bool
    {
        if (is_string($url) && str_starts_with($url, '/') && !str_starts_with($url, '//') && !str_contains($url, '\\')) {
            return true;
        }

        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true);
    }
}
