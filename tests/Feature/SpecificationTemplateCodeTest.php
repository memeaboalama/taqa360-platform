<?php

namespace Tests\Feature;

use App\Domain\Catalog\Models\Specification;
use Application\Admin\Livewire\Specification\Create;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SpecificationTemplateCodeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'template_code_test', 'database.connections.template_code_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        DB::purge('template_code_test');
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('status')->default('inactive');
            $table->text('meta')->nullable();
            $table->timestamps();
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
        });
        Schema::create('category_specification', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('specification_id');
        });
        Schema::create('specification_category_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specification_id');
            $table->string('category_type');
            $table->unsignedBigInteger('category_id');
            $table->boolean('is_excluded')->default(false);
        });
        Schema::create('specification_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specification_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('name');
            $table->string('status')->default('active');
            $table->text('value')->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();
        });
        $admin = new Admin(['type' => 'admin']);
        $admin->id = 1;
        $this->actingAs($admin, 'admin');
    }

    private function template(?string $code): Specification
    {
        return Specification::create([
            'code' => $code, 'name' => 'قالب قديم', 'status' => 'inactive',
            'meta' => ['name_ar' => 'قالب قديم', 'name_en' => 'Legacy template'],
        ]);
    }

    public function test_editing_a_legacy_template_saves_its_automatically_generated_code(): void
    {
        $template = $this->template(null);
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $this->assertStringStartsWith('TPL-', $editor->code);
        $editor->nameEn = 'Updated legacy template';
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->has('code'));
        $this->assertSame($editor->code, $template->fresh()->code);
        $this->assertSame('Updated legacy template', $template->fresh()->meta['name_en']);
    }

    public function test_edit_save_preserves_an_empty_code_without_validation_errors(): void
    {
        $template = $this->template('');
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $editor->code = '';
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->has('code'));
        $this->assertSame('', $template->fresh()->code);
    }

    public function test_editing_preserves_an_existing_code(): void
    {
        $template = $this->template('ORIGINAL-CODE');
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $this->assertSame('ORIGINAL-CODE', $editor->code);
        $editor->code = 'CHANGED-HIDDEN-VALUE';
        $editor->save();
        $this->assertSame('ORIGINAL-CODE', $template->fresh()->code);
    }

    public function test_editing_does_not_validate_the_hidden_code_value(): void
    {
        $template = $this->template('ORIGINAL-CODE');
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $editor->code = str_repeat('x', 100);
        $editor->nameEn = 'Updated template';
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->has('code'));
        $this->assertSame('ORIGINAL-CODE', $template->fresh()->code);
        $this->assertSame('Updated template', $template->fresh()->meta['name_en']);
    }

    public function test_new_template_still_gets_an_automatically_generated_code(): void
    {
        $editor = new Create();
        $editor->mount();
        $editor->nameAr = 'قالب جديد';
        $editor->nameEn = 'New template';
        $editor->code = '';
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->has('code'));
        $this->assertStringStartsWith('TPL-', Specification::query()->sole()->code);
    }

    public function test_field_width_is_loaded_edited_saved_and_copied_with_the_template(): void
    {
        $template = $this->template('WIDTH-TEMPLATE');
        $property = $template->properties()->create([
            'name' => 'القدرة', 'status' => 'active',
            'meta' => ['name_ar' => 'القدرة', 'name_en' => 'Power', 'input_type' => 'decimal', 'column_span' => 3],
        ]);
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $this->assertSame(3, $editor->properties[0]['column_span']);
        $editor->editProperty(0);
        $this->assertSame(3, $editor->propertyForm['column_span']);
        $editor->propertyForm['column_span'] = 9;
        $editor->saveProperty();
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->any());
        $this->assertSame(9, $property->fresh()->meta['column_span']);

        $copy = new Create();
        $copy->mount($template->id, 'copy');
        $this->assertSame(9, $copy->properties[0]['column_span']);
        $copy->save();
        $copiedTemplate = Specification::query()->where('id', '!=', $template->id)->sole();
        $this->assertSame(9, $copiedTemplate->properties()->first()->meta['column_span']);
    }

    public function test_new_field_width_defaults_to_six_and_rejects_values_outside_two_to_twelve(): void
    {
        foreach ([1, 13, 'invalid'] as $invalidWidth) {
            $editor = new Create();
            $editor->mount();
            $editor->addProperty();
            $this->assertSame(6, $editor->propertyForm['column_span']);
            $editor->propertyForm['name_ar'] = 'القدرة';
            $editor->propertyForm['name_en'] = 'Power';
            $editor->propertyForm['column_span'] = $invalidWidth;
            try {
                $editor->saveProperty();
                $this->fail('Invalid field widths must be rejected.');
            } catch (\Illuminate\Validation\ValidationException $exception) {
                $this->assertArrayHasKey('propertyForm.column_span', $exception->errors());
            }
        }
    }

    public function test_merchant_preview_uses_a_real_single_choice_dropdown(): void
    {
        $html = view('admin::livewire.specification.partials.choice-preview', ['property' => [
            'name_ar' => 'نوع اللوح', 'name_en' => 'Panel type', 'input_type' => 'select', 'options' => ['Mono', 'Poly'],
        ]])->render();
        $this->assertStringContainsString('<select class="form-select"', $html);
        $this->assertStringContainsString('<option value="Mono">Mono</option>', $html);
        $this->assertStringNotContainsString('disabled', $html);
        $this->assertStringNotContainsString('wire:model', $html);
    }

    public function test_merchant_preview_uses_a_checkbox_dropdown_for_multiple_choices(): void
    {
        $html = view('admin::livewire.specification.partials.choice-preview', ['property' => [
            'name_ar' => 'الموصلات', 'name_en' => 'Connectors', 'input_type' => 'multiselect', 'options' => ['A', 'B'],
        ]])->render();
        $this->assertStringContainsString('<details class="editor-multiselect"', $html);
        $this->assertSame(2, substr_count($html, 'type="checkbox"'));
        $this->assertStringContainsString('value="A"', $html);
        $this->assertStringContainsString('value="B"', $html);
        $this->assertStringNotContainsString('wire:model', $html);
    }

    public function test_drag_order_is_saved_without_recreating_fields_or_changing_conditions(): void
    {
        $template = $this->template('SORT-TEMPLATE');
        $keys = [(string) \Illuminate\Support\Str::uuid(), (string) \Illuminate\Support\Str::uuid()];
        $first = $template->properties()->create(['name' => 'الأول', 'meta' => [
            'name_ar' => 'الأول', 'name_en' => 'First', 'field_key' => $keys[0], 'input_type' => 'short_text',
        ]]);
        $second = $template->properties()->create(['name' => 'الثاني', 'meta' => [
            'name_ar' => 'الثاني', 'name_en' => 'Second', 'field_key' => $keys[1], 'input_type' => 'short_text',
            'is_conditional' => true, 'condition_field_key' => $keys[0], 'condition_operator' => 'filled',
        ]]);
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $editor->reorderProperties(array_reverse($keys));
        $this->assertSame('Second', $editor->properties[0]['name_en']);
        $this->assertSame($keys[0], $editor->properties[0]['condition_field_key']);
        $editor->save();
        $this->assertFalse($editor->getErrorBag()->any());
        $this->assertSame([$second->id, $first->id], $template->properties()->pluck('id')->all());
        $this->assertSame(0, $second->fresh()->sort_order);
        $this->assertSame(1, $first->fresh()->sort_order);
    }

    public function test_reordering_rejects_missing_duplicate_and_unknown_fields(): void
    {
        $template = $this->template('SORT-VALIDATION');
        foreach (['First', 'Second'] as $name) {
            $template->properties()->create(['name' => $name, 'meta' => [
                'name_ar' => $name, 'name_en' => $name, 'field_key' => (string) \Illuminate\Support\Str::uuid(),
            ]]);
        }
        $editor = new Create();
        $editor->mount($template->id, 'edit');
        $keys = array_column($editor->properties, 'field_key');
        foreach ([[$keys[0]], [$keys[0], $keys[0]], [$keys[0], 'unknown']] as $invalid) {
            try {
                $editor->reorderProperties($invalid);
                $this->fail('An invalid reorder request must be rejected.');
            } catch (\Illuminate\Validation\ValidationException $exception) {
                $this->assertSame($keys, array_column($editor->properties, 'field_key'));
            }
        }
    }
}
