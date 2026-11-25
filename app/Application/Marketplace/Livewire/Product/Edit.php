<?php

namespace Application\Marketplace\Livewire\Product;

use App\Domain\Catalog\Models\Specification;
use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\UpdateItemAction;
use Application\Marketplace\Livewire\Product\Modal\Update;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Edit extends Update
{
    protected $listeners = [];

    public $activeTab = 'details';
    public $inputs = [];
    public $propertyUploads = [];
    public $showPreview = false;
    public $saved = false;
    private array $storedAttachmentPaths = [];

    public function mount(?int $merchantId = null, ?int $productId = null): void
    {
        parent::mount((int) auth('merchant')->id());
        $this->updateProduct((int) $productId, app(FetchSingleItemAction::class));
    }

    public function updateProduct($id, FetchSingleItemAction $action): void
    {
        $this->merchantId = (int) auth('merchant')->id();
        abort_unless($this->merchantId && $id && $action->handle((int) $id, $this->merchantId), 404);
        parent::updateProduct($id, $action);
    }

    protected function productLoaded(): void
    {
        $this->refreshApplicableSpecifications();
        $this->inputs = [];
        foreach ($this->specificationFields as $property) {
            $value = $this->specification_values[$property->id] ?? null;
            if (in_array($property->meta['input_type'] ?? '', ['multiselect', 'number_range'], true)) {
                $value = is_string($value) ? json_decode($value, true) : $value;
                $value = is_array($value) ? $value : [];
            }
            $this->inputs[$property->id] = $value;
        }
    }

    protected function refreshApplicableSpecifications(): void
    {
        // Resolve templates on the server so required fields cannot be skipped
        // by changing the public selection in a Livewire request.
        $this->masterSpecifications = Specification::query()->isMaster()
            ->where('status', 'active')
            ->forCatalogCategories(
                $this->main_category_id ? (int) $this->main_category_id : null,
                $this->sub_category_id ? (int) $this->sub_category_id : null,
                $this->child_category_id ? (int) $this->child_category_id : null
            )
            ->with(['properties' => fn ($query) => $query->whereNull('item_id')->where('status', 'active')])
            ->orderBy('id')->get();
        $this->master_specification_ids = $this->masterSpecifications->modelKeys();
        unset($this->specificationFields, $this->visibleFields, $this->completion);
    }

    public function updatedMainCategoryId($value): void
    {
        parent::updatedMainCategoryId($value);
        $this->syncCategoryFields();
    }

    public function updatedSubCategoryId($value): void
    {
        parent::updatedSubCategoryId($value);
        $this->syncCategoryFields();
    }

    public function updatedChildCategoryId(): void
    {
        parent::updatedChildCategoryId();
        $this->syncCategoryFields();
    }

    private function syncCategoryFields(): void
    {
        $this->master_specification_ids = $this->masterSpecifications->modelKeys();
        $keys = array_fill_keys($this->specificationFields->pluck('id')->all(), true);
        $this->inputs = array_intersect_key($this->inputs, $keys);
        $this->propertyUploads = array_intersect_key($this->propertyUploads, $keys);
        $this->resetValidation();
        $this->saved = false;
    }

    public function getSpecificationFieldsProperty(): Collection
    {
        return collect($this->masterSpecifications)
            ->whereIn('id', array_map('intval', $this->master_specification_ids))
            ->flatMap(fn ($specification) => $specification->properties)
            ->unique('id')->values();
    }

    public function getVisibleFieldsProperty(): Collection
    {
        return $this->specificationFields->filter(fn ($property) => $this->fieldVisible($property))->values();
    }

    public function fieldVisible($property, array $visited = []): bool
    {
        $meta = $property->meta ?? [];
        if (empty($meta['is_conditional'])) {
            return true;
        }
        if (in_array($property->id, $visited, true)) {
            return false;
        }
        $visited[] = $property->id;
        $source = $this->specificationFields->first(fn ($field) =>
            ($field->meta['field_key'] ?? null) === ($meta['condition_field_key'] ?? '')
        );
        if (!$source || !$this->fieldVisible($source, $visited)) {
            return false;
        }
        $value = $this->inputs[$source->id] ?? null;
        $expected = (string) ($meta['condition_value'] ?? '');
        $selected = is_array($value) ? array_map('strval', $value) : [(string) $value];
        $expectedValues = array_map('trim', preg_split('/[,،\n]+/u', $expected));
        $filled = $this->hasFieldValue($value);

        return match ($meta['condition_operator'] ?? 'equals') {
            'filled' => $filled,
            'equals', 'contains_option' => $filled && in_array($expected, $selected, true),
            'not_equals' => $filled && !in_array($expected, $selected, true),
            'in_selected_values' => $filled && count(array_intersect($selected, $expectedValues)) > 0,
            'out_selected_values' => $filled && count(array_intersect($selected, $expectedValues)) === 0,
            default => false,
        };
    }

    public function hasFieldValue($value): bool
    {
        if (is_array($value)) {
            return count($value) > 0 && collect($value)->every(fn ($part) => $this->hasFieldValue($part));
        }
        return $value !== null && trim((string) $value) !== '';
    }

    public function fieldName($property): string
    {
        return $property->meta['name_' . app()->getLocale()] ?? $property->name;
    }

    public function getCompletionProperty(): array
    {
        $required = $this->visibleFields->filter(fn ($property) => !empty($property->meta['is_required']));
        $filled = $required->filter(fn ($property) =>
            isset($this->propertyUploads[$property->id]) || $this->hasFieldValue($this->inputs[$property->id] ?? null)
        )->count();

        return ['total' => $required->count(), 'filled' => $filled,
            'percent' => $required->isEmpty() ? 0 : (int) round($filled / $required->count() * 100)];
    }

    protected function rules(): array
    {
        $rules = parent::rules();
        // A merchant may have no configured service pricing rows.
        $rules['item_prices'] = ['present', 'array'];
        foreach ($this->visibleFields as $property) {
            $meta = $property->meta ?? [];
            $key = 'inputs.' . $property->id;
            $required = $this->status === 'active' && !empty($meta['is_required']);
            $presence = $required ? 'required' : 'nullable';
            $type = $meta['input_type'] ?? 'short_text';
            $rules[$key] = match ($type) {
                'decimal' => [$presence, 'numeric'],
                'integer' => [$presence, 'integer'],
                'date' => [$presence, 'date_format:Y-m-d'],
                'boolean' => [$presence, Rule::in(['0', '1'])],
                'select' => [$presence, Rule::in($meta['options'] ?? [])],
                'multiselect' => [$presence, 'array'],
                'number_range' => [$presence, 'array'],
                default => [$presence, 'string', 'max:5000'],
            };
            if ($type === 'multiselect') {
                $rules[$key . '.*'] = ['string', 'distinct', Rule::in($meta['options'] ?? [])];
            }
            if ($type === 'number_range') {
                $hasRange = $this->hasFieldValue(array_filter($this->inputs[$property->id] ?? [], fn ($value) => $value !== null && $value !== ''));
                $rangePresence = $required || $hasRange ? 'required' : 'nullable';
                $rules[$key . '.min'] = [$rangePresence, 'numeric'];
                $rules[$key . '.max'] = [$rangePresence, 'numeric', 'gte:' . $key . '.min'];
            }
            if ($type === 'attachment') {
                $rules[$key] = ['nullable', 'string'];
                $rules['propertyUploads.' . $property->id] = [
                    $required && !$this->hasFieldValue($this->inputs[$property->id] ?? null) ? 'required' : 'nullable',
                    'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240',
                ];
            }
        }
        return $rules;
    }

    public function nickNames()
    {
        $names = parent::nickNames();
        foreach ($this->specificationFields as $property) {
            foreach (['inputs.', 'propertyUploads.'] as $prefix) {
                $names[$prefix . $property->id] = $this->fieldName($property);
            }
            $names['inputs.' . $property->id . '.min'] = $this->fieldName($property);
            $names['inputs.' . $property->id . '.max'] = $this->fieldName($property);
        }
        return $names;
    }

    public function publish(UpdateItemAction $action): void
    {
        $this->status = 'active';
        $this->store($action);
    }

    public function saveDraft(UpdateItemAction $action): void
    {
        $this->status = 'inactive';
        $this->store($action);
    }

    public function store(UpdateItemAction $action): void
    {
        // Restore ownership from the authenticated session on every save.
        $this->merchantId = (int) auth('merchant')->id();
        $this->merchant_id = $this->merchantId;
        abort_unless($this->merchantId && app(FetchSingleItemAction::class)->handle((int) $this->product_id, $this->merchantId), 404);
        $this->refreshApplicableSpecifications();
        $this->saved = false;
        $this->storedAttachmentPaths = [];
        try {
            parent::store($action);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->activeTab = collect(array_keys($exception->errors()))->contains(fn ($key) =>
                str_starts_with($key, 'inputs.') || str_starts_with($key, 'propertyUploads.')
            ) ? 'specifications' : 'details';
            throw $exception;
        } finally {
            if (!$this->saved && $this->storedAttachmentPaths) {
                Storage::disk('fileDisk')->delete($this->storedAttachmentPaths);
            }
        }
    }

    protected function prepareUpdateData(array $data): array
    {
        $values = [];
        foreach ($this->specificationFields as $property) {
            $value = $this->fieldVisible($property) ? ($this->inputs[$property->id] ?? null) : null;
            if ($this->fieldVisible($property) && isset($this->propertyUploads[$property->id])) {
                $path = $this->propertyUploads[$property->id]->store('upload/products/specifications', 'fileDisk');
                if (!$path) {
                    throw new \RuntimeException('Unable to store specification attachment.');
                }
                $this->storedAttachmentPaths[] = $path;
                $value = Storage::disk('fileDisk')->url($path);
            }
            $values[$property->id] = is_array($value)
                ? ($this->hasFieldValue($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null)
                : $value;
        }
        $data['specification_values'] = $values;
        unset($data['inputs'], $data['propertyUploads']);
        return $data;
    }

    protected function productUpdated(): void
    {
        $this->saved = true;
        $this->reset(['image', 'additional_images', 'specification_file', 'propertyUploads']);
        $this->updateProduct($this->product_id, app(FetchSingleItemAction::class));
        $this->alert('success', __('admin::main.messages.product_updated'));
        $this->dispatchBrowserEvent('product-editor-saved');
    }

    public function render()
    {
        return view('marketplace::livewire.product.edit');
    }
}
