<?php

namespace Tests\Feature;

use App\Application\Ecommerce\Support\ProductPagePresentation;
use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Models\SpecificationProperty;
use Domain\MarketPlace\Models\Item;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductPagePresentationTest extends TestCase
{
    private function product(array $fields = [], array $files = []): Item
    {
        $product = new Item(['title' => ['ar' => 'لوح شمسي', 'en' => 'Solar panel'],
            'description' => ['ar' => 'وصف المنتج', 'en' => 'Product description'],
            'default_price' => 100, 'sale_price' => 80, 'is_on_sale' => true, 'stock' => 20]);
        $product->id = 10;
        $specification = new Specification();
        $specification->setRelation('properties', collect($fields)->map(fn ($field) => new SpecificationProperty($field)));
        $product->setRelation('catalogSpecifications', collect([$specification]));
        $product->setRelation('files', collect($files)->map(fn ($file) => new Attachment($file)));
        foreach (['merchant', 'mainCategory', 'subCategory', 'childCategory'] as $relation) {
            $product->setRelation($relation, null);
        }
        $product->setRelation('activeItemPrices', collect());
        return $product;
    }

    public function test_gallery_excludes_documents_and_deduplicates_images(): void
    {
        $presentation = ProductPagePresentation::make($this->product([], [
            ['collection_name' => 'item_specification', 'url' => '/storage/spec.pdf'],
            ['collection_name' => 'item_additional_image', 'url' => '/storage/panel.jpg'],
            ['collection_name' => 'item_additional_image', 'url' => '/storage/panel.jpg'],
            ['collection_name' => 'item_additional_image', 'url' => 'javascript:alert(1)'],
        ]));
        $this->assertSame(['/storage/panel.jpg'], $presentation['images']->all());
        $this->assertCount(1, $presentation['documents']);
    }

    public function test_specs_preserve_zero_skip_empty_fields_and_separate_documents(): void
    {
        $presentation = ProductPagePresentation::make($this->product([
            ['name' => 'Zero', 'value' => '0', 'meta' => ['group' => '', 'show_in_summary' => true]],
            ['name' => 'Empty', 'value' => '', 'meta' => ['show_in_summary' => true]],
            ['name' => 'Sheet', 'value' => 'https://example.com/spec.pdf', 'meta' => ['input_type' => 'attachment']],
            ['name' => 'Unsafe', 'value' => 'javascript:alert(1)', 'meta' => ['input_type' => 'attachment']],
        ]));
        $this->assertCount(1, $presentation['groups']);
        $this->assertSame('0', $presentation['groups']->first()->first()->value);
        $this->assertCount(1, $presentation['highlights']);
        $this->assertCount(1, $presentation['documents']);
    }

    public function test_overview_renders_real_multiselect_values_and_active_sale_price_in_both_languages(): void
    {
        Cache::put('store_currency_iso', 'sar');
        $product = $this->product([
            ['name' => 'Connections', 'value' => '["MC4","USB"]', 'meta' => ['input_type' => 'multiselect', 'name_ar' => 'التوصيلات', 'name_en' => 'Connections', 'show_in_summary' => true]],
        ]);
        foreach (['ar', 'en'] as $locale) {
            app()->setLocale($locale);
            $html = view('ecommerce::pages.product.partials.overview', compact('product') + ['isFavorite' => false])->render();
            $this->assertStringContainsString('MC4', $html);
            $this->assertStringContainsString('USB', $html);
            $this->assertStringContainsString('80', $html);
            $this->assertStringContainsString('<del>', $html);
            $this->assertStringNotContainsString('ecommerce::', $html);
            $this->assertStringContainsString('id="pp-panel-specifications"', $html);
        }
    }

    public function test_service_prices_have_a_tab_before_documents_and_missing_prices_do_not_use_product_price(): void
    {
        Cache::put('store_currency_iso', 'sar');
        $product = $this->product();
        $prices = collect();
        foreach ([['Delivery', 250], ['Installation', null], ['Maintenance', ''], ['Inspection', 0]] as [$name, $price]) {
            $itemPrice = new \App\Domain\MarketPlace\Models\ItemPrices(['price' => $price]);
            $itemPrice->setRelation('type_service', new \App\Domain\AppBase\Models\TypeService(['name' => $name]));
            $prices->push($itemPrice);
        }
        $product->setRelation('activeItemPrices', $prices);
        $html = view('ecommerce::pages.product.partials.service-prices', compact('product'))->render();
        $this->assertStringContainsString('250', $html);
        $this->assertSame(3, substr_count($html, __('ecommerce::ecommerce.price_not_specified')));
        $this->assertStringNotContainsString('100', $html);
        $overview = view('ecommerce::pages.product.partials.overview', compact('product') + ['isFavorite' => false])->render();
        $this->assertLessThan(strpos($overview, 'id="pp-tab-documents"'), strpos($overview, 'id="pp-tab-service_prices"'));
        $this->assertStringContainsString('id="pp-panel-service_prices" role="tabpanel" aria-labelledby="pp-tab-service_prices" hidden', $overview);
        $this->assertGreaterThan(strpos($overview, 'id="pp-panel-service_prices"'), strpos($overview, 'class="pp-service-prices"'));
    }

    public function test_service_price_query_requires_both_price_and_service_to_be_active(): void
    {
        $query = (new Item())->activeItemPrices();
        $this->assertStringContainsString('type_services', $query->toSql());
        $this->assertSame(2, count(array_filter($query->getBindings(), fn ($binding) => $binding === 'active')));
    }

    public function test_highlights_follow_current_template_flags_without_changing_product_values(): void
    {
        $product = $this->product([
            ['name' => 'Model', 'value' => 'Panel-620', 'meta' => ['master_property_id' => 1, 'show_in_summary' => false]],
            ['name' => 'Voltage', 'value' => '42', 'meta' => ['master_property_id' => 2, 'show_in_summary' => true]],
        ]);
        $fields = $product->catalogSpecifications->first()->properties;
        $fields[0]->setRelation('masterProperty', new SpecificationProperty(['meta' => ['show_in_summary' => true]]));
        $fields[1]->setRelation('masterProperty', new SpecificationProperty(['meta' => ['show_in_summary' => false]]));

        $highlights = ProductPagePresentation::make($product)['highlights'];

        $this->assertCount(1, $highlights);
        $this->assertSame('Panel-620', $highlights->first()->value);
        $this->assertFalse($fields[0]->meta['show_in_summary']);
        $this->assertSame(1, $fields[0]->master_property_id);
    }
}
