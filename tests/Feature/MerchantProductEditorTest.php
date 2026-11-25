<?php

namespace Tests\Feature;

use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Models\SpecificationProperty;
use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\UpdateItemAction;
use Application\Marketplace\Controllers\ProductController;
use Application\Marketplace\Livewire\Product\Edit;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class MerchantProductEditorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'product_editor_test', 'database.connections.product_editor_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        DB::purge('product_editor_test');
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('title');
        });
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('meta')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('status')->default('active');
        });
        Schema::create('category_specification', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('specification_id');
        });
        Schema::create('specification_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specification_id');
            $table->string('name');
            $table->text('value')->nullable();
            $table->text('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('status')->default('active');
        });
        $editor = $this->editor();
        DB::table('categories')->insert($editor->categories[0]->getAttributes());
        DB::table('specifications')->insert($editor->masterSpecifications[0]->getAttributes());
        DB::table('category_specification')->insert(['category_id' => 1, 'specification_id' => 50]);
        foreach ($editor->specificationFields as $property) {
            DB::table('specification_properties')->insert($property->getAttributes() + ['specification_id' => 50]);
        }
    }

    private function editor(): MerchantProductEditorFixture
    {
        $editor = new MerchantProductEditorFixture();
        $editor->mount();
        return $editor;
    }

    public function test_editor_renders_as_a_page_with_categories_and_existing_data(): void
    {
        Livewire::test(MerchantProductEditorFixture::class)
            ->assertSee('Solar panel')
            ->assertSee('Solar products')
            ->assertSee(__('marketplace::products.classification'))
            ->assertSee(__('marketplace::products.completion'))
            ->assertDontSee('id="updateProduct"', false)
            ->set('activeTab', 'specifications')
            ->assertSee('Power')
            ->assertSee('class="col-12 col-md-3"', false)
            ->assertSee('class="col-12 col-md-9"', false)
            ->assertSee('W');
    }

    public function test_conditional_fields_and_completion_include_false_and_zero_values(): void
    {
        $editor = $this->editor();
        $editor->inputs = [301 => '0', 302 => '0'];
        $this->assertFalse($editor->fieldVisible($editor->specificationFields[2]));
        $this->assertSame(['total' => 2, 'filled' => 2, 'percent' => 100], $editor->getCompletionProperty());

        $editor = $this->editor();
        $editor->inputs = [301 => '620', 302 => '1', 303 => ''];
        $this->assertTrue($editor->fieldVisible($editor->specificationFields[2]));
        $this->assertSame(['total' => 3, 'filled' => 2, 'percent' => 67], $editor->getCompletionProperty());
    }

    public function test_multiselect_preserves_multiple_options_across_livewire_updates(): void
    {
        Livewire::test(MerchantProductEditorFixture::class)
            ->set('activeTab', 'specifications')
            ->set('inputs.304', ['A'])
            ->assertSet('inputs.304', ['A'])
            ->set('inputs.304', ['A', 'B'])
            ->assertSet('inputs.304', ['A', 'B'])
            ->assertSee('A, B')
            ->set('inputs.301', '620')
            ->assertSet('inputs.304', ['A', 'B']);
    }

    public function test_publishing_requires_visible_required_fields_but_drafts_allow_missing_values(): void
    {
        $editor = $this->editor();
        $editor->inputs = [301 => '', 302 => '0'];
        $rules = $editor->validationRules();
        $validator = Validator::make(['inputs' => $editor->inputs], array_filter($rules, fn ($key) => str_starts_with($key, 'inputs.'), ARRAY_FILTER_USE_KEY));
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('inputs.301'));
        $this->assertFalse($validator->errors()->has('inputs.303'));

        $editor->status = 'inactive';
        $this->assertContains('nullable', $editor->validationRules()['inputs.301']);
    }

    public function test_hidden_fields_are_cleared_and_structured_values_round_trip_as_json(): void
    {
        $editor = $this->editor();
        $editor->inputs = [301 => '620', 302 => '0', 303 => 'Old hidden value', 304 => ['A', 'B'], 305 => ['min' => '0', 'max' => '50']];
        $data = $editor->payload();
        $this->assertNull($data['specification_values'][303]);
        $this->assertSame(['A', 'B'], json_decode($data['specification_values'][304], true));
        $this->assertSame(['min' => '0', 'max' => '50'], json_decode($data['specification_values'][305], true));
    }

    public function test_range_and_option_validation_rejects_invalid_values(): void
    {
        $editor = $this->editor();
        $editor->inputs = [301 => '620', 302 => '0', 304 => ['Unknown'], 305 => ['min' => '20', 'max' => '10']];
        $rules = array_filter($editor->validationRules(), fn ($key) => str_starts_with($key, 'inputs.'), ARRAY_FILTER_USE_KEY);
        $validator = Validator::make(['inputs' => $editor->inputs], $rules);
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('inputs.304.0'));
        $this->assertTrue($validator->errors()->has('inputs.305.max'));
    }

    public function test_preview_shows_entered_visible_fields_and_hides_empty_or_conditional_values(): void
    {
        Livewire::test(MerchantProductEditorFixture::class)
            ->set('inputs.301', '620')
            ->set('inputs.302', '0')
            ->set('inputs.303', 'HIDDEN-SPECIFICATION-VALUE')
            ->set('showPreview', true)
            ->assertSee('620')
            ->assertSee(__('marketplace::products.no'))
            ->assertDontSee('HIDDEN-SPECIFICATION-VALUE');
    }

    public function test_edit_controller_checks_current_merchant_ownership(): void
    {
        $merchant = new Merchant();
        $merchant->id = 41;
        $this->actingAs($merchant, 'merchant');
        $action = $this->mock(FetchSingleItemAction::class);
        $action->shouldReceive('handle')->once()->with(25, 41)->andReturn(new Item());
        $view = app(ProductController::class)->edit(25, $action);
        $this->assertSame(25, $view->getData()['productId']);
    }

    public function test_save_pipeline_publishes_values_and_keeps_the_product_loaded(): void
    {
        $merchant = new Merchant();
        $merchant->id = 41;
        $this->actingAs($merchant, 'merchant');
        $this->mock(FetchSingleItemAction::class)->shouldReceive('handle')->once()->with(25, 41)->andReturn(new Item());
        $action = $this->mock(UpdateItemAction::class);
        $action->shouldReceive('handle')->once()->withArgs(fn ($id, $data, $owner) =>
            $id === 25 && $owner === 41 && $data['status'] === 'active'
            && $data['specification_values'][301] === '620'
            && !isset($data['inputs'])
        )->andReturn(new Item());
        $editor = new MerchantProductSaveFixture();
        $editor->mount();
        $editor->main_category_id = 1;
        $editor->inputs = [301 => '620', 302 => '0'];
        $editor->publish($action);
        $this->assertTrue($editor->saved);
        $this->assertSame('Solar panel', $editor->title['en']);
        $this->assertSame(25, $editor->product_id);
    }

    public function test_structured_specifications_display_as_customer_readable_values(): void
    {
        $property = new SpecificationProperty(['name' => 'Power', 'value' => '620', 'meta' => ['unit' => 'W']]);
        $this->assertSame('620 W', $property->display_value);
        $property->meta = ['input_type' => 'multiselect'];
        $property->value = '["A","B"]';
        $this->assertSame('A، B', $property->display_value);
        $property->meta = ['input_type' => 'number_range', 'unit' => 'V'];
        $property->value = '{"min":"0","max":"50"}';
        $this->assertSame('0 – 50 V', $property->display_value);
        $property->meta = ['input_type' => 'boolean'];
        $property->value = '0';
        $this->assertSame(__('marketplace::products.no'), $property->display_value);
    }

    public function test_draft_save_allows_incomplete_specifications_and_saves_inactive_status(): void
    {
        $merchant = new Merchant();
        $merchant->id = 41;
        $this->actingAs($merchant, 'merchant');
        $this->mock(FetchSingleItemAction::class)->shouldReceive('handle')->once()->with(25, 41)->andReturn(new Item());
        $action = $this->mock(UpdateItemAction::class);
        $action->shouldReceive('handle')->once()->withArgs(fn ($id, $data, $owner) =>
            $id === 25 && $owner === 41 && $data['status'] === 'inactive'
            && $data['specification_values'][301] === null
        )->andReturn(new Item());
        $editor = new MerchantProductSaveFixture();
        $editor->mount();
        $editor->main_category_id = 1;
        $editor->saveDraft($action);
        $this->assertTrue($editor->saved);
        $this->assertSame('inactive', $editor->status);
    }

    public function test_required_templates_cannot_be_removed_from_a_publish_request(): void
    {
        $merchant = new Merchant();
        $merchant->id = 41;
        $this->actingAs($merchant, 'merchant');
        $this->mock(FetchSingleItemAction::class)->shouldReceive('handle')->once()->with(25, 41)->andReturn(new Item());
        $action = $this->mock(UpdateItemAction::class);
        $action->shouldNotReceive('handle');
        $editor = new MerchantProductSaveFixture();
        $editor->mount();
        $editor->main_category_id = 1;
        $editor->master_specification_ids = [];
        $editor->masterSpecifications = new Collection();
        try {
            $editor->publish($action);
            $this->fail('Publishing must require the category template fields.');
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->assertArrayHasKey('inputs.301', $exception->errors());
            $this->assertSame('specifications', $editor->activeTab);
        }
    }

    public function test_edit_controller_returns_not_found_for_another_merchants_product(): void
    {
        $merchant = new Merchant();
        $merchant->id = 41;
        $this->actingAs($merchant, 'merchant');
        $action = $this->mock(FetchSingleItemAction::class);
        $action->shouldReceive('handle')->once()->with(99, 41)->andReturnNull();
        $this->expectException(HttpException::class);
        app(ProductController::class)->edit(99, $action);
    }
}

class MerchantProductEditorFixture extends Edit
{
    public function mount(?int $merchantId = null, ?int $productId = null): void
    {
        app()->setLocale('en');
        $this->product_id = 25;
        $this->title = ['ar' => 'لوح شمسي', 'en' => 'Solar panel'];
        $this->description = ['ar' => 'وصف المنتج التجريبي', 'en' => 'Solar panel description'];
        $this->default_price = 340;
        $this->stock = 20;
        $category = new Category(['title' => ['ar' => 'منتجات شمسية', 'en' => 'Solar products']]);
        $category->id = 1;
        $this->categories = new Collection([$category]);
        $this->subcategories = $this->childCategories = $this->addition_groups = new Collection();
        $specification = new Specification(['name' => 'Solar specifications']);
        $specification->id = 50;
        $properties = [];
        foreach ([
            301 => ['name_en' => 'Power', 'input_type' => 'decimal', 'unit' => 'W', 'is_required' => true, 'field_key' => 'power', 'column_span' => 3],
            302 => ['name_en' => 'Warranty', 'input_type' => 'boolean', 'is_required' => true, 'field_key' => 'warranty', 'column_span' => 9],
            303 => ['name_en' => 'Warranty details', 'input_type' => 'short_text', 'is_required' => true, 'is_conditional' => true, 'condition_field_key' => 'warranty', 'condition_operator' => 'equals', 'condition_value' => '1'],
            304 => ['name_en' => 'Connectors', 'input_type' => 'multiselect', 'options' => ['A', 'B']],
            305 => ['name_en' => 'Temperature', 'input_type' => 'number_range'],
            306 => ['name_en' => 'Datasheet', 'input_type' => 'attachment'],
            307 => ['name_en' => 'Notes', 'input_type' => 'long_text'],
            308 => ['name_en' => 'Cell count', 'input_type' => 'integer'],
            309 => ['name_en' => 'Production date', 'input_type' => 'date'],
            310 => ['name_en' => 'Panel type', 'input_type' => 'select', 'options' => ['Mono', 'Poly']],
        ] as $id => $meta) {
            $property = new SpecificationProperty(['name' => $meta['name_en'], 'meta' => $meta]);
            $property->id = $id;
            $properties[] = $property;
        }
        $specification->setRelation('properties', new Collection($properties));
        $this->masterSpecifications = new Collection([$specification]);
        $this->master_specification_ids = [50];
    }

    public function validationRules(): array
    {
        return $this->rules();
    }

    public function payload(): array
    {
        return $this->prepareUpdateData([]);
    }
}

class MerchantProductSaveFixture extends MerchantProductEditorFixture
{
    protected function productUpdated(): void
    {
        $this->saved = true;
    }
}
