<?php

namespace Tests\Feature;

use App\Application\Ecommerce\Support\MerchantSpecificationFilters;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MerchantSpecificationFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'merchant_filter_test', 'database.connections.merchant_filter_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        DB::purge('merchant_filter_test');
        Schema::create('items', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('merchant_id'); $table->string('title');
            $table->string('status'); $table->integer('stock')->nullable(); $table->integer('sub_category_id'); $table->softDeletes();
        });
        foreach (['merchants', 'merchant_branches'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id(); $table->string('status'); $table->unsignedBigInteger('merchant_id')->nullable(); $table->softDeletes();
            });
        }
        Schema::create('specifications', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('item_id')->nullable(); $table->string('status');
        });
        Schema::create('specification_properties', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('specification_id'); $table->unsignedBigInteger('item_id')->nullable();
            $table->string('status'); $table->string('name'); $table->text('value')->nullable(); $table->text('meta');
            $table->integer('sort_order')->default(0);
        });
        DB::table('merchants')->insert(['id' => 1, 'status' => 'active']);
        DB::table('merchant_branches')->insert(['merchant_id' => 1, 'status' => '1']);
        DB::table('specifications')->insert(['id' => 1, 'item_id' => null, 'status' => 'active']);
        $this->property(1, 1, null, 'Voltage', null, ['filterable' => true, 'unit' => 'V']);
        $this->property(2, 1, null, 'Connections', null, ['filterable' => true, 'input_type' => 'multiselect']);
        $this->property(3, 1, null, 'Hidden', null, ['filterable' => false]);
        foreach ([1, 2, 3, 4, 5] as $id) {
            DB::table('items')->insert(['id' => $id, 'merchant_id' => $id === 4 ? 2 : 1,
                'title' => json_encode(['en' => 'Panel ' . $id]), 'status' => $id === 5 ? 'inactive' : 'active',
                'stock' => 10, 'sub_category_id' => $id === 3 ? 20 : 10]);
            DB::table('specifications')->insert(['id' => 10 + $id, 'item_id' => $id, 'status' => 'active']);
            $this->property(10 + $id, 10 + $id, $id, 'Voltage', $id === 4 ? '999' : ($id === 3 ? '0' : '12'), ['master_property_id' => 1, 'filterable' => false]);
            $this->property(20 + $id, 10 + $id, $id, 'Connections', $id === 1 ? '["MC4","USB"]' : '["USB"]', ['master_property_id' => 2]);
            $this->property(30 + $id, 10 + $id, $id, 'Hidden', 'hidden value', ['master_property_id' => 3, 'filterable' => true]);
        }
    }

    private function property(int $id, int $specification, ?int $item, string $name, ?string $value, array $meta): void
    {
        DB::table('specification_properties')->insert(['id' => $id, 'specification_id' => $specification,
            'item_id' => $item, 'name' => $name, 'value' => $value, 'meta' => json_encode($meta), 'status' => 'active']);
    }

    private function facets(array $filters = []): \Illuminate\Support\Collection
    {
        return app(MerchantSpecificationFilters::class)->facets(app(ItemRepository::class)->filterMerchantItems($filters, 1));
    }

    public function test_facets_use_current_template_settings_and_only_public_products_of_this_merchant(): void
    {
        $facets = $this->facets();
        $this->assertSame([1, 2], $facets->keys()->all());
        $this->assertSame(['0', '12'], array_column($facets[1]['options'], 'value'));
        $this->assertSame('0 V', $facets[1]['options'][0]['label']);
        $this->assertCount(2, $facets[1]['options'][1]['item_ids']);
        $this->assertSame(['MC4', 'USB'], array_column($facets[2]['options'], 'value'));
        DB::table('specification_properties')->where('id', 1)->update(['meta' => json_encode(['filterable' => false])]);
        $this->assertFalse($this->facets()->has(1));
    }

    public function test_multiple_values_use_any_match_within_field_and_all_matches_between_fields(): void
    {
        $service = app(MerchantSpecificationFilters::class);
        $facets = $this->facets();
        $selected = $service->selection($facets, [1 => ['12', '0'], 2 => ['MC4']]);
        $ids = $service->matchingItemIds($facets, $selected);
        $this->assertSame([1], $ids);
        $this->assertSame([1], app(ItemRepository::class)->filterMerchantItems(['specification_item_ids' => $ids], 1)->pluck('id')->all());
        $this->assertSame([], $service->matchingItemIds($facets, [1 => ['0'], 2 => ['MC4']]));
        $this->assertSame(0, app(ItemRepository::class)->filterMerchantItems(['specification_item_ids' => []], 1)->count());
        $this->assertNull($service->matchingItemIds($facets, []));
    }

    public function test_category_search_and_invalid_parameters_are_respected(): void
    {
        $service = app(MerchantSpecificationFilters::class);
        $facets = $this->facets(['category' => '10', 'q' => 'Panel 1']);
        $this->assertSame(['12'], array_column($facets[1]['options'], 'value'));
        $this->assertSame([], $service->selection($facets, 'invalid'));
        $this->assertSame([], $service->selection($facets, [1 => ['999', ['nested']], 3 => ['hidden value']]));
        $this->assertSame([1 => ['12']], $service->selection($facets, [1 => ['12', '12']]));
    }

    public function test_filter_form_renders_selected_values_and_clear_link(): void
    {
        $this->app->instance('request', \Illuminate\Http\Request::create('/ecommerce/merchant/1', 'GET', [
            'q' => 'Panel', 'category' => '10', 'sort_by' => 'price_low', 'page' => 3,
        ]));
        $html = view('ecommerce::pages.merchant.partials.specification-filters', [
            'specificationFacets' => $this->facets(), 'selectedSpecifications' => [1 => ['12']],
            'merchant' => (object) ['id' => 1],
        ])->render();
        $this->assertStringContainsString('name="specs[1][]" value="12" data-selected-label="12 V"', $html);
        $this->assertStringContainsString('data-field="2"', $html);
        $this->assertStringNotContainsString('value="MC4"', $html);
        $this->assertStringContainsString(__('ecommerce::merchant_filters.clear'), $html);
        $this->assertStringNotContainsString('hidden value', $html);
        $this->assertStringContainsString('name="q" value="Panel"', $html);
        $this->assertStringContainsString('name="sort_by" value="price_low"', $html);
        $this->assertStringNotContainsString('name="page"', $html);
    }

    public function test_empty_inactive_and_attachment_fields_are_not_available_as_filters(): void
    {
        DB::table('specification_properties')->where('id', 1)->update(['status' => 'inactive']);
        $this->assertFalse($this->facets()->has(1));
        DB::table('specification_properties')->where('id', 1)->update(['status' => 'active', 'meta' => json_encode(['filterable' => true, 'input_type' => 'attachment'])]);
        $this->assertFalse($this->facets()->has(1));
        DB::table('specification_properties')->where('item_id', '>', 0)->update(['value' => null]);
        $this->assertTrue($this->facets()->isEmpty());
    }

    public function test_options_are_searched_and_loaded_in_pages_of_thirty_without_product_ids(): void
    {
        for ($i = 0; $i < 65; $i++) {
            $id = 100 + $i;
            DB::table('items')->insert(['id' => $id, 'merchant_id' => 1, 'title' => 'Panel', 'status' => 'active', 'stock' => 10, 'sub_category_id' => 10]);
            DB::table('specifications')->insert(['id' => $id + 100, 'item_id' => $id, 'status' => 'active']);
            $this->property($id + 200, $id + 100, $id, 'Voltage', sprintf('Value %03d', $i), ['master_property_id' => 1]);
        }
        $service = app(MerchantSpecificationFilters::class);
        $facets = $service->facets(app(ItemRepository::class)->filterMerchantItems([], 1), 1);
        $one = $service->optionPage($facets, 1, '', 1);
        $two = $service->optionPage($facets, 1, '', 2);
        $three = $service->optionPage($facets, 1, '', 3);
        $this->assertCount(30, $one['results']);
        $this->assertCount(30, $two['results']);
        $this->assertCount(7, $three['results']);
        $this->assertTrue($one['has_more']);
        $this->assertFalse($three['has_more']);
        $this->assertCount(67, array_unique(array_column(array_merge($one['results'], $two['results'], $three['results']), 'value')));
        $this->assertArrayNotHasKey('item_ids', $one['results'][0]);
        $this->assertCount(5, $service->optionPage($facets, 1, 'VALUE 06', 1)['results']);
        $this->assertSame(['results' => [], 'has_more' => false], $service->optionPage($facets, 999, '', 1));
    }

    public function test_options_endpoint_scopes_values_to_merchant_category_and_current_filterable_fields(): void
    {
        $controller = app(\App\Application\Ecommerce\Controllers\MerchantController::class);
        $merchant = \Domain\MarketPlace\Models\Merchant::findOrFail(1);
        $request = \Illuminate\Http\Request::create('/options', 'GET', ['field' => '1', 'category' => '10']);
        $data = $controller->specificationOptions($merchant, $request)->getData(true);
        $this->assertSame(['12'], array_column($data['results'], 'value'));
        $this->assertFalse($data['has_more']);
        $hidden = \Illuminate\Http\Request::create('/options', 'GET', ['field' => '3']);
        $this->assertSame([], $controller->specificationOptions($merchant, $hidden)->getData(true)['results']);
    }
}
