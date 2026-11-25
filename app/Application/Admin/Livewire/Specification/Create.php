<?php

namespace Application\Admin\Livewire\Specification;

use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Models\SpecificationCategoryLink;
use App\Domain\Catalog\Models\SpecificationProperty;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    private const PAGE_SIZE = 30;
    private const PROPERTY_INPUT_TYPES = [
        'short_text',
        'decimal',
        'integer',
        'select',
        'multiselect',
        'boolean',
        'long_text',
        'attachment',
        'number_range',
        'date',
    ];

    public string $code = '';
    public string $mode = 'create';
    public ?int $specificationId = null;
    public string $activeTab = 'settings';
    public string $nameAr = '';
    public string $nameEn = '';
    public string $description = '';
    public string $family = '';
    public string $status = 'inactive';
    public string $categorySearch = '';
    public array $properties = [];
    public array $propertyForm = [];
    public ?int $editingPropertyIndex = null;
    public bool $showPropertyModal = false;
    public array $categorySelections = [];
    public array $expandedCategoryIds = [];
    public array $expandedSubcategoryIds = [];
    public array $subcategoryLimits = [];
    public array $childCategoryLimits = [];
    public int $categoryLimit = self::PAGE_SIZE;
    public bool $showMerchantPreview = false;

    public function mount(?int $specificationId = null, string $mode = 'create'): void
    {
        abort_unless(in_array($mode, ['create', 'edit', 'copy'], true), 404);
        $this->mode = $mode;
        $this->specificationId = $specificationId;
        $this->authorizeAccess();

        if ($mode === 'create') {
            $this->code = $this->generateCode();

            return;
        }

        $specification = Specification::query()
            ->isMaster()
            ->with(['categories', 'categoryLinks', 'properties'])
            ->findOrFail($specificationId);

        $this->loadSpecification($specification, $mode === 'copy');
    }

    private function loadSpecification(Specification $specification, bool $asCopy): void
    {
        $meta = $specification->meta ?? [];
        $this->code = $asCopy || blank($specification->code)
            ? $this->generateCode()
            : (string) $specification->code;
        $this->nameAr = (string) ($meta['name_ar'] ?? $specification->name ?? '');
        $this->nameEn = (string) ($meta['name_en'] ?? '');
        $this->description = (string) ($specification->description ?? '');
        $this->family = (string) ($meta['family'] ?? '');
        $this->status = $asCopy ? 'inactive' : (string) ($specification->getRawOriginal('status') ?: 'inactive');

        if ($asCopy) {
            $this->nameAr .= ' (' . __('admin::home.specification_copy_suffix_ar') . ')';
            $this->nameEn .= ' (' . __('admin::home.specification_copy_suffix_en') . ')';
        }

        $this->categorySelections = $specification->categories
            ->map(fn ($category) => ['type' => 'category', 'id' => (int) $category->id, 'excluded' => false])
            ->all();
        foreach ($specification->categoryLinks as $link) {
            $this->categorySelections[] = [
                'type' => $link->category_type,
                'id' => (int) $link->category_id,
                'excluded' => (bool) $link->is_excluded,
            ];
        }
        $selectedSubcategoryIds = collect($this->categorySelections)
            ->where('type', 'subcategory')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $selectedChildCategoryIds = collect($this->categorySelections)
            ->where('type', 'child_category')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $childSubcategoryIds = $selectedChildCategoryIds
            ? ChildCategory::query()->whereIn('id', $selectedChildCategoryIds)->pluck('subcategory_id')->map(fn ($id) => (int) $id)->all()
            : [];
        $selectedSubcategoryIds = array_values(array_unique(array_merge($selectedSubcategoryIds, $childSubcategoryIds)));
        $subcategoryRootIds = $selectedSubcategoryIds
            ? Subcategory::query()->whereIn('id', $selectedSubcategoryIds)->pluck('category_id')->map(fn ($id) => (int) $id)->all()
            : [];
        $this->expandedCategoryIds = array_values(array_unique(array_merge(
            collect($this->categorySelections)->where('type', 'category')->pluck('id')->map(fn ($id) => (int) $id)->all(),
            $subcategoryRootIds
        )));
        $this->expandedSubcategoryIds = $selectedSubcategoryIds;

        $sourceProperties = $specification->properties
            ->whereNull('item_id')
            ->values()
            ->map(function (SpecificationProperty $property) use ($asCopy): array {
                $propertyMeta = $property->meta ?? [];

                return [
                    'property_id' => $asCopy ? null : (int) $property->id,
                    'name_ar' => (string) ($propertyMeta['name_ar'] ?? $property->name ?? ''),
                    'name_en' => (string) ($propertyMeta['name_en'] ?? ''),
                    'field_key' => (string) ($propertyMeta['field_key'] ?? Str::uuid()),
                    'input_type' => $propertyMeta['input_type'] ?? 'short_text',
                    'column_span' => (int) ($propertyMeta['column_span'] ?? (($propertyMeta['input_type'] ?? '') === 'long_text' ? 12 : 6)),
                    'unit' => $propertyMeta['unit'] ?? '',
                    'group' => $propertyMeta['group'] ?? '',
                    'merchant_guidance' => $propertyMeta['merchant_guidance'] ?? '',
                    'is_required' => (bool) ($propertyMeta['is_required'] ?? false),
                    'show_in_summary' => (bool) ($propertyMeta['show_in_summary'] ?? false),
                    'filterable' => (bool) ($propertyMeta['filterable'] ?? false),
                    'is_conditional' => (bool) ($propertyMeta['is_conditional'] ?? false),
                    'options' => is_array($propertyMeta['options'] ?? null) ? $propertyMeta['options'] : [],
                    'condition_field_key' => $propertyMeta['condition_field_key'] ?? null,
                    'condition_operator' => $propertyMeta['condition_operator'] ?? null,
                    'condition_value' => $propertyMeta['condition_value'] ?? null,
                ];
            });

        if ($asCopy) {
            $fieldKeyMap = $sourceProperties->mapWithKeys(
                fn ($property) => [$property['field_key'] => (string) Str::uuid()]
            );
            $this->properties = $sourceProperties
                ->map(function (array $property) use ($fieldKeyMap): array {
                    $property['field_key'] = $fieldKeyMap[$property['field_key']];
                    $conditionKey = $property['condition_field_key'];
                    $property['condition_field_key'] = $conditionKey ? ($fieldKeyMap[$conditionKey] ?? null) : null;

                    return $property;
                })
                ->all();

            return;
        }

        $this->properties = $sourceProperties->all();
        $this->ensurePropertyFieldKeys();
    }

    public function updatedCategorySearch(): void
    {
        $this->validateOnly('categorySearch', [
            'categorySearch' => ['nullable', 'string', 'max:100'],
        ]);
        $this->categoryLimit = self::PAGE_SIZE;
        $this->expandedCategoryIds = [];
        $this->expandedSubcategoryIds = [];
        $this->subcategoryLimits = [];
        $this->childCategoryLimits = [];
    }

    public function loadMoreCategories(): void
    {
        $this->categoryLimit += self::PAGE_SIZE;
    }

    public function toggleCategory(string $categoryType, int $categoryId): void
    {
        abort_unless(in_array($categoryType, ['category', 'subcategory', 'child_category'], true), 404);

        $category = match ($categoryType) {
            'category' => Category::query()->findOrFail($categoryId),
            'subcategory' => Subcategory::query()->findOrFail($categoryId),
            'child_category' => ChildCategory::query()->findOrFail($categoryId),
        };
        $parentCategoryId = match ($categoryType) {
            'category' => null,
            'subcategory' => (int) $category->category_id,
            'child_category' => (int) Subcategory::query()->findOrFail($category->subcategory_id)->category_id,
        };
        $state = $this->categorySelectionState($categoryType, $categoryId);
        $select = $state !== 'checked';

        if ($categoryType === 'category') {
            $this->removeDescendantSelectionsForCategory($categoryId);
            if ($select) {
                $this->setCategorySelection('category', $categoryId, false);
            } else {
                $this->removeCategorySelection('category', $categoryId);
            }

            return;
        }

        if ($categoryType === 'subcategory') {
            $this->removeChildSelectionsForSubcategory($categoryId);
            if ($select) {
                if ($this->hasSelectedRootCategory((int) $parentCategoryId)) {
                    $this->removeCategorySelection('subcategory', $categoryId);
                } else {
                    $this->setCategorySelection('subcategory', $categoryId, false);
                }
            } elseif ($this->hasSelectedRootCategory((int) $parentCategoryId)) {
                $this->setCategorySelection('subcategory', $categoryId, true);
            } else {
                $this->removeCategorySelection('subcategory', $categoryId);
            }

            return;
        }

        $subcategory = Subcategory::query()->findOrFail($category->subcategory_id);
        $subcategorySelection = $this->findCategorySelection('subcategory', (int) $subcategory->id);
        $inherited = $subcategorySelection
            ? !(bool) ($subcategorySelection['excluded'] ?? false)
            : $this->hasSelectedRootCategory((int) $subcategory->category_id);

        if ($select) {
            $this->setCategorySelection('child_category', $categoryId, false);
        } elseif ($inherited) {
            $this->setCategorySelection('child_category', $categoryId, true);
        } else {
            $this->removeCategorySelection('child_category', $categoryId);
        }
    }

    public function toggleCategoryExpanded(int $categoryId): void
    {
        Category::query()->findOrFail($categoryId);

        if (in_array($categoryId, $this->expandedCategoryIds, true)) {
            $this->expandedCategoryIds = array_values(array_diff($this->expandedCategoryIds, [$categoryId]));

            return;
        }

        $this->expandedCategoryIds[] = $categoryId;
        $this->subcategoryLimits[$categoryId] = max(self::PAGE_SIZE, (int) ($this->subcategoryLimits[$categoryId] ?? self::PAGE_SIZE));
    }

    public function loadMoreSubcategories(int $categoryId): void
    {
        Category::query()->findOrFail($categoryId);
        $this->subcategoryLimits[$categoryId] = min(
            10000,
            (int) ($this->subcategoryLimits[$categoryId] ?? self::PAGE_SIZE) + self::PAGE_SIZE
        );
    }

    public function toggleSubcategoryExpanded(int $subcategoryId): void
    {
        $subcategory = Subcategory::query()->findOrFail($subcategoryId);

        if (in_array($subcategoryId, $this->expandedSubcategoryIds, true)) {
            $this->expandedSubcategoryIds = array_values(array_diff($this->expandedSubcategoryIds, [$subcategoryId]));

            return;
        }

        $this->expandedSubcategoryIds[] = $subcategoryId;
        $this->childCategoryLimits[$subcategoryId] = max(self::PAGE_SIZE, (int) ($this->childCategoryLimits[$subcategoryId] ?? self::PAGE_SIZE));
        $this->expandedCategoryIds[] = $subcategory->category_id;
        $this->expandedCategoryIds = array_values(array_unique(array_map('intval', $this->expandedCategoryIds)));
    }

    public function loadMoreChildCategories(int $subcategoryId): void
    {
        Subcategory::query()->findOrFail($subcategoryId);
        $this->childCategoryLimits[$subcategoryId] = min(
            10000,
            (int) ($this->childCategoryLimits[$subcategoryId] ?? self::PAGE_SIZE) + self::PAGE_SIZE
        );
    }

    public function addProperty(): void
    {
        $this->ensurePropertyFieldKeys();
        $this->editingPropertyIndex = null;
        $this->propertyForm = $this->emptyPropertyForm();
        $this->resetValidation();
        $this->showPropertyModal = true;
        $this->activeTab = 'fields';
    }

    public function editProperty(int $index): void
    {
        abort_unless(array_key_exists($index, $this->properties), 404);

        $this->ensurePropertyFieldKeys();
        $property = $this->properties[$index];
        $this->editingPropertyIndex = $index;
        $this->propertyForm = [
            'property_id' => $property['property_id'] ?? null,
            'name_ar' => $property['name_ar'] ?? '',
            'name_en' => $property['name_en'] ?? '',
            'input_type' => $property['input_type'] ?? 'short_text',
            'column_span' => (int) ($property['column_span'] ?? (($property['input_type'] ?? '') === 'long_text' ? 12 : 6)),
            'unit' => $property['unit'] ?? '',
            'group' => $property['group'] ?? '',
            'merchant_guidance' => $property['merchant_guidance'] ?? '',
            'is_required' => (bool) ($property['is_required'] ?? false),
            'show_in_summary' => (bool) ($property['show_in_summary'] ?? false),
            'filterable' => (bool) ($property['filterable'] ?? false),
            'is_conditional' => (bool) ($property['is_conditional'] ?? false),
            'options_text' => implode("\n", $property['options'] ?? []),
            'condition_field_key' => $property['condition_field_key'] ?? '',
            'condition_operator' => $property['condition_operator'] ?? 'equals',
            'condition_value' => $property['condition_value'] ?? '',
        ];
        if (empty($this->properties[$index]['field_key'])) {
            $this->properties[$index]['field_key'] = (string) Str::uuid();
        }
        $this->resetValidation();
        $this->showPropertyModal = true;
        $this->activeTab = 'fields';
    }

    public function saveProperty(): void
    {
        $isConditional = (bool) ($this->propertyForm['is_conditional'] ?? false);
        $availableFieldKeys = collect($this->properties)
            ->filter(fn ($property, $index) => $index !== $this->editingPropertyIndex)
            ->pluck('field_key')
            ->filter()
            ->values()
            ->all();
        if ($isConditional && $availableFieldKeys === []) {
            $this->addError('propertyForm.is_conditional', __('admin::home.specification_field_conditional_requires_other_field'));

            return;
        }

        $conditionOperator = $this->propertyForm['condition_operator'] ?? null;
        $conditionNeedsValue = $isConditional && $conditionOperator !== 'filled';

        $validated = $this->validate([
            'propertyForm.name_ar' => ['required', 'string', 'max:255'],
            'propertyForm.name_en' => ['required', 'string', 'max:255'],
            'propertyForm.input_type' => ['required', 'in:' . implode(',', self::PROPERTY_INPUT_TYPES)],
            'propertyForm.column_span' => ['required', 'integer', 'between:2,12'],
            'propertyForm.unit' => ['nullable', 'string', 'max:100'],
            'propertyForm.group' => ['nullable', 'string', 'max:255'],
            'propertyForm.merchant_guidance' => ['nullable', 'string', 'max:2000'],
            'propertyForm.is_required' => ['required', 'boolean'],
            'propertyForm.show_in_summary' => ['required', 'boolean'],
            'propertyForm.filterable' => ['required', 'boolean'],
            'propertyForm.is_conditional' => ['required', 'boolean'],
            'propertyForm.options_text' => [
                in_array($this->propertyForm['input_type'] ?? null, ['select', 'multiselect'], true) ? 'required' : 'nullable',
                'string',
                'max:5000',
            ],
            'propertyForm.condition_field_key' => $isConditional
                ? ['required', 'string', \Illuminate\Validation\Rule::in($availableFieldKeys)]
                : ['nullable', 'string'],
            'propertyForm.condition_operator' => $isConditional
                ? ['required', 'in:' . implode(',', array_keys($this->conditionOperators()))]
                : ['nullable', 'string'],
            'propertyForm.condition_value' => $conditionNeedsValue
                ? ['required', 'string', 'max:255']
                : ['nullable', 'string', 'max:255'],
        ], [
            'propertyForm.name_ar.required' => __('admin::home.specification_field_name_ar_required'),
            'propertyForm.name_en.required' => __('admin::home.specification_field_name_en_required'),
            'propertyForm.input_type.required' => __('admin::home.specification_input_type_required'),
            'propertyForm.options_text.required' => __('admin::home.specification_field_options_required'),
            'propertyForm.condition_field_key.required' => __('admin::home.specification_condition_field_required'),
            'propertyForm.condition_field_key.in' => __('admin::home.specification_condition_field_invalid'),
            'propertyForm.condition_operator.required' => __('admin::home.specification_condition_operator_required'),
            'propertyForm.condition_value.required' => __('admin::home.specification_condition_value_required'),
        ], [
            'propertyForm.name_ar' => __('admin::home.specification_field_name_ar'),
            'propertyForm.name_en' => __('admin::home.specification_field_name_en'),
            'propertyForm.input_type' => __('admin::home.specification_input_type'),
            'propertyForm.column_span' => __('admin::home.specification_field_column_span'),
            'propertyForm.unit' => __('admin::home.specification_field_unit'),
            'propertyForm.group' => __('admin::home.specification_field_group'),
            'propertyForm.merchant_guidance' => __('admin::home.specification_field_merchant_guidance'),
            'propertyForm.options_text' => __('admin::home.specification_field_options'),
            'propertyForm.condition_field_key' => __('admin::home.specification_condition_depends_on'),
            'propertyForm.condition_operator' => __('admin::home.specification_condition_operator'),
            'propertyForm.condition_value' => __('admin::home.specification_condition_value'),
        ])['propertyForm'];

        $options = preg_split('/\r\n|\r|\n/', trim((string) ($validated['options_text'] ?? '')));
        $property = [
            'property_id' => $this->editingPropertyIndex === null
                ? null
                : ($this->properties[$this->editingPropertyIndex]['property_id'] ?? null),
            'name_ar' => trim($validated['name_ar']),
            'name_en' => trim($validated['name_en']),
            'input_type' => $validated['input_type'],
            'column_span' => (int) $validated['column_span'],
            'unit' => trim((string) ($validated['unit'] ?? '')),
            'group' => trim((string) ($validated['group'] ?? '')),
            'merchant_guidance' => trim((string) ($validated['merchant_guidance'] ?? '')),
            'is_required' => (bool) $validated['is_required'],
            'show_in_summary' => (bool) $validated['show_in_summary'],
            'filterable' => (bool) $validated['filterable'],
            'is_conditional' => (bool) $validated['is_conditional'],
            'options' => in_array($validated['input_type'], ['select', 'multiselect'], true)
                ? array_values(array_filter(array_map('trim', $options), fn ($option) => $option !== ''))
                : [],
            'condition_field_key' => (bool) $validated['is_conditional'] ? $validated['condition_field_key'] : null,
            'condition_operator' => (bool) $validated['is_conditional'] ? $validated['condition_operator'] : null,
            'condition_value' => (bool) $validated['is_conditional'] && $validated['condition_operator'] !== 'filled'
                ? trim((string) $validated['condition_value'])
                : null,
        ];

        foreach ($property['options'] as $option) {
            if (mb_strlen($option) > 255) {
                $this->addError('propertyForm.options_text', __('validation.max.string', [
                    'attribute' => __('admin::home.specification_field_options'),
                    'max' => 255,
                ]));

                return;
            }
        }

        if (in_array($validated['input_type'], ['select', 'multiselect'], true) && $property['options'] === []) {
            $this->addError('propertyForm.options_text', __('admin::home.specification_field_options_required'));

            return;
        }

        if ($this->editingPropertyIndex === null) {
            $property['field_key'] = (string) Str::uuid();
            $this->properties[] = $property;
        } elseif (array_key_exists($this->editingPropertyIndex, $this->properties)) {
            $property['field_key'] = $this->properties[$this->editingPropertyIndex]['field_key'] ?? (string) Str::uuid();
            $this->properties[$this->editingPropertyIndex] = $property;
            $this->properties = array_values($this->properties);
        } else {
            abort(404);
        }

        $this->closePropertyModal();
    }

    public function closePropertyModal(): void
    {
        $this->showPropertyModal = false;
        $this->editingPropertyIndex = null;
        $this->propertyForm = [];
        $this->resetValidation();
    }

    public function removeProperty(int $index): void
    {
        if (array_key_exists($index, $this->properties)) {
            $removedFieldKey = $this->properties[$index]['field_key'] ?? null;
            unset($this->properties[$index]);
            $this->properties = array_values($this->properties);

            if ($removedFieldKey) {
                foreach ($this->properties as &$property) {
                    if (($property['condition_field_key'] ?? null) === $removedFieldKey) {
                        $property['is_conditional'] = false;
                        $property['condition_field_key'] = null;
                        $property['condition_operator'] = null;
                        $property['condition_value'] = null;
                    }
                }
                unset($property);
            }
        }
    }

    public function movePropertyUp(int $index): void
    {
        if ($index <= 0 || !array_key_exists($index, $this->properties)) {
            return;
        }

        [$this->properties[$index - 1], $this->properties[$index]] = [
            $this->properties[$index],
            $this->properties[$index - 1],
        ];
    }

    public function reorderProperties(array $fieldKeys): void
    {
        $this->authorizeAccess();
        $this->ensurePropertyFieldKeys();
        $fieldKeys = array_values($fieldKeys);
        $properties = collect($this->properties)->keyBy('field_key');
        validator(['fieldKeys' => $fieldKeys], [
            'fieldKeys' => ['required', 'array', 'size:' . $properties->count()],
            'fieldKeys.*' => ['required', 'string', 'distinct', \Illuminate\Validation\Rule::in($properties->keys()->all())],
        ])->validate();

        $editingKey = $this->editingPropertyIndex !== null
            ? ($this->properties[$this->editingPropertyIndex]['field_key'] ?? null)
            : null;
        $this->properties = array_map(fn ($key) => $properties[$key], $fieldKeys);
        if ($editingKey !== null) {
            $this->editingPropertyIndex = array_search($editingKey, $fieldKeys, true);
        }
    }

    public function movePropertyDown(int $index): void
    {
        if (!array_key_exists($index, $this->properties) || !array_key_exists($index + 1, $this->properties)) {
            return;
        }

        [$this->properties[$index], $this->properties[$index + 1]] = [
            $this->properties[$index + 1],
            $this->properties[$index],
        ];
    }

    public function togglePreview(): void
    {
        $this->activeTab = 'fields';
        $this->ensurePropertyFieldKeys();
        $this->showMerchantPreview = true;
    }

    public function closeMerchantPreview(): void
    {
        $this->showMerchantPreview = false;
    }

    public function save()
    {
        try {
            return $this->saveTemplate();
        } catch (QueryException $exception) {
            report($exception);
            $this->notifySaveError(__('admin::home.specification_save_failed'));
        }
    }

    private function saveTemplate()
    {
        $this->authorizeAccess();

        if ($this->mode !== 'edit' && blank($this->code)) {
            $this->code = $this->generateCode();
        }

        if ($this->status === 'active' && empty($this->categorySelections)) {
            $message = __('admin::home.specification_publish_requirements');
            $this->addError('publishRequirements', $message);
            $this->notifySaveError($message);
            $this->activeTab = 'categories';

            return;
        }

        if ($this->status === 'active' && empty($this->properties)) {
            $message = __('admin::home.specification_publish_requirements');
            $this->addError('publishRequirements', $message);
            $this->notifySaveError($message);
            $this->activeTab = 'fields';

            return;
        }

        if (trim($this->nameAr) === '' || trim($this->nameEn) === '') {
            $this->activeTab = 'settings';
        } elseif ($this->status === 'active') {
            foreach ($this->properties as $property) {
                if (trim((string) ($property['name_ar'] ?? '')) === '' || trim((string) ($property['name_en'] ?? '')) === '') {
                    $this->activeTab = 'fields';
                    break;
                }
            }
        }

        $data = $this->validateForSave([
            'code' => $this->mode === 'edit' ? ['exclude'] : ['required', 'string', 'max:32'],
            'nameAr' => ['required', 'string', 'max:255'],
            'nameEn' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'family' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive,draft,rejected'],
            'categorySelections' => ['array'],
            'categorySelections.*.type' => ['required', 'in:category,subcategory,child_category'],
            'categorySelections.*.id' => ['required', 'integer'],
            'categorySelections.*.excluded' => ['nullable', 'boolean'],
            'properties' => ['array'],
            'properties.*.property_id' => ['nullable', 'integer'],
            'properties.*.name_ar' => ['required', 'string', 'max:255'],
            'properties.*.name_en' => ['required', 'string', 'max:255'],
            'properties.*.input_type' => ['required', 'in:' . implode(',', self::PROPERTY_INPUT_TYPES)],
            'properties.*.column_span' => ['nullable', 'integer', 'between:2,12'],
            'properties.*.unit' => ['nullable', 'string', 'max:100'],
            'properties.*.group' => ['nullable', 'string', 'max:255'],
            'properties.*.merchant_guidance' => ['nullable', 'string', 'max:2000'],
            'properties.*.is_required' => ['required', 'boolean'],
            'properties.*.show_in_summary' => ['required', 'boolean'],
            'properties.*.filterable' => ['required', 'boolean'],
            'properties.*.is_conditional' => ['required', 'boolean'],
            'properties.*.field_key' => ['required', 'uuid', 'distinct'],
            'properties.*.options' => ['nullable', 'array'],
            'properties.*.options.*' => ['string', 'max:255'],
            'properties.*.condition_field_key' => ['nullable', 'string'],
            'properties.*.condition_operator' => ['nullable', 'in:' . implode(',', array_keys($this->conditionOperators()))],
            'properties.*.condition_value' => ['nullable', 'string', 'max:255'],
        ], [
            'nameAr.required' => __('admin::home.specification_template_name_ar_required'),
            'nameEn.required' => __('admin::home.specification_template_name_en_required'),
            'properties.*.name_ar.required' => __('admin::home.specification_field_name_ar_required'),
            'properties.*.name_en.required' => __('admin::home.specification_field_name_en_required'),
        ], [
            'code' => __('admin::home.specification_template_code'),
            'nameAr' => __('admin::home.specification_name_ar'),
            'nameEn' => __('admin::home.specification_name_en'),
            'properties.*.name_ar' => __('admin::home.specification_field_name_ar'),
            'properties.*.name_en' => __('admin::home.specification_field_name_en'),
            'properties.*.column_span' => __('admin::home.specification_field_column_span'),
        ]);
        if ($data === null) {
            return;
        }

        if ($data['status'] === 'active' && (empty($data['categorySelections']) || empty($data['properties']))) {
            $message = __('admin::home.specification_publish_requirements');
            $this->addError('publishRequirements', $message);
            $this->notifySaveError($message);
            $this->activeTab = empty($data['properties']) ? 'fields' : 'categories';

            return;
        }

        $selectionKeys = [];
        foreach ($data['categorySelections'] as $index => $selection) {
            if ($selection['type'] === 'category' && ($selection['excluded'] ?? false)) {
                $message = __('admin::home.specification_invalid_category_selection');
                $this->addError("categorySelections.$index.excluded", $message);
                $this->notifySaveError($message);

                return;
            }

            $table = [
                'category' => 'categories',
                'subcategory' => 'sub_categories',
                'child_category' => 'child_categories',
            ][$selection['type']];
            $selectionKey = $selection['type'] . ':' . $selection['id'];
            if (isset($selectionKeys[$selectionKey])) {
                $this->addError("categorySelections.$index.id", __('admin::home.specification_duplicate_category_link'));

                return;
            }
            $selectionKeys[$selectionKey] = true;

            if ($this->validateForSave([
                "categorySelections.$index.id" => ['required', 'integer', \Illuminate\Validation\Rule::exists($table, 'id')],
            ]) === null) {
                $this->activeTab = 'categories';

                return;
            }
        }

        $propertyNames = [];
        $propertyKeys = collect($data['properties'])->pluck('field_key')->all();
        foreach ($data['properties'] as $index => $property) {
            $nameAr = trim((string) ($property['name_ar'] ?? ''));
            $nameEn = trim((string) ($property['name_en'] ?? ''));

            if ($nameAr === '' && $nameEn === '' && $data['status'] === 'inactive') {
                continue;
            }

            if ($nameAr === '' || $nameEn === '') {
                $message = __('admin::home.specification_both_field_names_required');
                $this->addError("properties.$index.name_ar", $message);
                $this->notifySaveError($message);
                $this->activeTab = 'fields';

                return;
            }

            $inputType = $property['input_type'] ?? 'short_text';
            $options = array_values(array_filter(
                array_map('trim', $property['options'] ?? []),
                fn ($option) => $option !== ''
            ));
            if (in_array($inputType, ['select', 'multiselect'], true) && $options === []) {
                $message = __('admin::home.specification_field_options_required');
                $this->addError("properties.$index.options", $message);
                $this->notifySaveError($message);
                $this->activeTab = 'fields';

                return;
            }

            if ($property['is_conditional'] ?? false) {
                if (empty($property['condition_field_key'])
                    || $property['condition_field_key'] === $property['field_key']
                    || !in_array($property['condition_field_key'], $propertyKeys, true)) {
                    $message = __('admin::home.specification_condition_field_invalid');
                    $this->addError("properties.$index.condition_field_key", $message);
                    $this->notifySaveError($message);
                    $this->activeTab = 'fields';

                    return;
                }

                if (empty($property['condition_operator'])) {
                    $message = __('admin::home.specification_condition_operator_required');
                    $this->addError("properties.$index.condition_operator", $message);
                    $this->notifySaveError($message);
                    $this->activeTab = 'fields';

                    return;
                }

                if ($property['condition_operator'] !== 'filled' && trim((string) ($property['condition_value'] ?? '')) === '') {
                    $message = __('admin::home.specification_condition_value_required');
                    $this->addError("properties.$index.condition_value", $message);
                    $this->notifySaveError($message);
                    $this->activeTab = 'fields';

                    return;
                }
            }

            $propertyNames[] = [
                'property_id' => $property['property_id'] ?? null,
                'name_ar' => $nameAr,
                'name_en' => $nameEn,
                'field_key' => $property['field_key'],
                'input_type' => $inputType,
                'column_span' => (int) ($property['column_span'] ?? ($inputType === 'long_text' ? 12 : 6)),
                'unit' => trim((string) ($property['unit'] ?? '')),
                'group' => trim((string) ($property['group'] ?? '')),
                'merchant_guidance' => trim((string) ($property['merchant_guidance'] ?? '')),
                'is_required' => (bool) ($property['is_required'] ?? false),
                'show_in_summary' => (bool) ($property['show_in_summary'] ?? false),
                'filterable' => (bool) ($property['filterable'] ?? false),
                'is_conditional' => (bool) ($property['is_conditional'] ?? false),
                'options' => $options,
                'condition_field_key' => $property['condition_field_key'] ?? null,
                'condition_operator' => $property['condition_operator'] ?? null,
                'condition_value' => $property['condition_value'] ?? null,
            ];
        }

        $isEditing = $this->mode === 'edit';
        DB::transaction(function () use ($data, $propertyNames, $isEditing): void {
            if ($isEditing) {
                $specification = Specification::query()
                    ->isMaster()
                    ->lockForUpdate()
                    ->findOrFail($this->specificationId);
                $meta = $specification->meta ?? [];
                $meta['name_ar'] = $data['nameAr'];
                $meta['name_en'] = $data['nameEn'];
                $meta['family'] = $data['family'] ?: null;
                $attributes = [
                    'name' => $data['nameAr'],
                    'description' => $data['description'] ?: null,
                    'status' => $data['status'],
                    'meta' => $meta,
                ];
                if (blank($specification->code)) {
                    $attributes['code'] = $this->code;
                }
                $specification->fill($attributes)->save();
            } else {
                $specification = Specification::query()->create([
                    'item_id' => null,
                    'code' => $data['code'],
                    'name' => $data['nameAr'],
                    'description' => $data['description'] ?: null,
                    'status' => $data['status'],
                    'meta' => [
                        'name_ar' => $data['nameAr'],
                        'name_en' => $data['nameEn'],
                        'family' => $data['family'] ?: null,
                    ],
                ]);
            }

            $rootCategoryIds = collect($data['categorySelections'])
                ->where('type', 'category')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $specification->categories()->sync($rootCategoryIds);
            $specification->categoryLinks()->delete();

            foreach ($data['categorySelections'] as $selection) {
                if ($selection['type'] === 'category') {
                    continue;
                }

                SpecificationCategoryLink::query()->create([
                    'specification_id' => $specification->id,
                    'category_type' => $selection['type'],
                    'category_id' => (int) $selection['id'],
                    'is_excluded' => (bool) ($selection['excluded'] ?? false),
                ]);
            }

            $existingProperties = $isEditing
                ? $specification->properties()->whereNull('item_id')->get()
                : collect();
            $existingById = $existingProperties->keyBy('id');
            $existingByFieldKey = $existingProperties->keyBy(fn ($property) => $property->meta['field_key'] ?? '');
            $retainedPropertyIds = [];

            foreach ($propertyNames as $sortOrder => $propertyName) {
                $propertyId = $propertyName['property_id'] ?? null;
                $property = $propertyId ? $existingById->get($propertyId) : null;
                if (!$property) {
                    $property = $existingByFieldKey->get($propertyName['field_key']);
                }

                unset($propertyName['property_id']);
                if ($property) {
                    $property->name = $propertyName['name_ar'];
                    $property->status = 'active';
                    $property->sort_order = $sortOrder;
                    $property->meta = $propertyName;
                    $property->save();
                    $retainedPropertyIds[] = $property->id;

                    continue;
                }

                $specification->properties()->create([
                    'name' => $propertyName['name_ar'],
                    'value' => null,
                    'status' => 'active',
                    'sort_order' => $sortOrder,
                    'meta' => $propertyName,
                ]);
            }

            if ($isEditing) {
                $existingProperties
                    ->reject(fn ($property) => in_array($property->id, $retainedPropertyIds, true))
                    ->each->delete();
            }
        });

        $message = $isEditing
            ? __('admin::home.Specification Updated Successfully')
            : __('admin::home.Specification Created Successfully');

        return $this->flash('success', $message, [
            'toast' => true,
            'position' => app()->getLocale() === 'ar' ? 'top-start' : 'top-end',
            'timer' => 3500,
            'timerProgressBar' => true,
            'showConfirmButton' => false,
        ], route('admin:vars.specifications'));
    }

    private function validateForSave(array $rules, array $messages = [], array $attributes = []): ?array
    {
        try {
            return $this->validate($rules, $messages, $attributes);
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->errors());
            $this->notifySaveError($exception->validator->errors()->first());

            return null;
        }
    }

    private function notifySaveError(string $message): void
    {
        $this->alert('error', $message, [
            'toast' => true,
            'position' => app()->getLocale() === 'ar' ? 'top-start' : 'top-end',
            'timer' => 5000,
            'timerProgressBar' => true,
            'showConfirmButton' => false,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->authorizeAccess();

        $search = trim($this->categorySearch);
        $categories = collect();
        $hasMoreCategories = false;
        if ($this->activeTab === 'categories') {
            $categoryQuery = Category::query()
                ->withCount('subcategories')
                ->orderBy('id');

            if ($search !== '') {
                $categoryQuery->where(function ($query) use ($search): void {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhereHas('subcategories', fn ($subcategories) => $subcategories->where('title', 'like', '%' . $search . '%'))
                        ->orWhereHas('subcategories.childCategories', fn ($children) => $children->where('title', 'like', '%' . $search . '%'));
                });
            }

            $categoryRows = $categoryQuery->limit($this->categoryLimit + 1)->get();
            $hasMoreCategories = $categoryRows->count() > $this->categoryLimit;
            $categories = $categoryRows->take($this->categoryLimit);
            if ($search === '' && $this->expandedCategoryIds !== []) {
                $pinnedCategories = Category::query()
                    ->whereIn('id', $this->expandedCategoryIds)
                    ->withCount('subcategories')
                    ->orderBy('id')
                    ->get();
                $categories = $categories->concat($pinnedCategories)->unique('id')->sortBy('id')->values();
            }
        }

        $subcategoriesByCategory = [];
        $hasMoreSubcategories = [];
        $expandedCategoryIds = $this->activeTab === 'categories'
            ? array_intersect($this->expandedCategoryIds, $categories->modelKeys())
            : [];
        foreach ($expandedCategoryIds as $categoryId) {
            $limit = min(10000, max(self::PAGE_SIZE, (int) ($this->subcategoryLimits[$categoryId] ?? self::PAGE_SIZE)));
            $rows = Subcategory::query()
                ->where('category_id', $categoryId)
                ->when($search !== '', fn ($query) => $query->where('title', 'like', '%' . $search . '%'))
                ->withCount('childCategories')
                ->orderBy('id')
                ->limit($limit + 1)
                ->get();

            $hasMoreSubcategories[$categoryId] = $rows->count() > $limit;
            $subcategories = $rows->take($limit);
            if ($search === '') {
                $pinnedSubcategories = Subcategory::query()
                    ->where('category_id', $categoryId)
                    ->whereIn('id', $this->expandedSubcategoryIds)
                    ->withCount('childCategories')
                    ->orderBy('id')
                    ->get();
                $subcategories = $subcategories->concat($pinnedSubcategories)->unique('id')->sortBy('id')->values();
            }
            $subcategoriesByCategory[$categoryId] = $subcategories;
        }

        $childCategoriesBySubcategory = [];
        $hasMoreChildCategories = [];
        $expandedSubcategoryIds = $this->activeTab === 'categories' ? $this->expandedSubcategoryIds : [];
        foreach ($expandedSubcategoryIds as $subcategoryId) {
            $parent = Subcategory::query()
                ->whereIn('category_id', $categories->modelKeys())
                ->find($subcategoryId);
            if (!$parent) {
                continue;
            }

            $limit = min(10000, max(self::PAGE_SIZE, (int) ($this->childCategoryLimits[$subcategoryId] ?? self::PAGE_SIZE)));
            $rows = ChildCategory::query()
                ->where('subcategory_id', $subcategoryId)
                ->when($search !== '', fn ($query) => $query->where('title', 'like', '%' . $search . '%'))
                ->orderBy('id')
                ->limit($limit + 1)
                ->get();

            $hasMoreChildCategories[$subcategoryId] = $rows->count() > $limit;
            $childCategories = $rows->take($limit);
            if ($search === '') {
                $selectedChildIds = collect($this->categorySelections)
                    ->where('type', 'child_category')
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                $pinnedChildren = ChildCategory::query()
                    ->where('subcategory_id', $subcategoryId)
                    ->whereIn('id', $selectedChildIds)
                    ->orderBy('id')
                    ->get();
                $childCategories = $childCategories->concat($pinnedChildren)->unique('id')->sortBy('id')->values();
            }
            $childCategoriesBySubcategory[$subcategoryId] = $childCategories;
        }

        $categoryStates = $this->buildCategoryStates($categories, $subcategoriesByCategory, $childCategoriesBySubcategory);

        return view('admin::livewire.specification.create', [
            'categories' => $categories,
            'hasMoreCategories' => $hasMoreCategories,
            'selectedSmallCategoryCount' => $this->selectedSmallCategoryCount(),
            'categoryStates' => $categoryStates,
            'propertyInputTypes' => $this->propertyInputTypes(),
            'conditionOperators' => $this->conditionOperators(),
            'subcategoriesByCategory' => $subcategoriesByCategory,
            'hasMoreSubcategories' => $hasMoreSubcategories,
            'childCategoriesBySubcategory' => $childCategoriesBySubcategory,
            'hasMoreChildCategories' => $hasMoreChildCategories,
        ]);
    }

    private function categorySelectionState(string $type, int $id): string
    {
        if ($type === 'category') {
            $states = $this->buildCategoryStates(collect([Category::query()->findOrFail($id)]), [], []);
        } elseif ($type === 'subcategory') {
            $subcategory = Subcategory::query()->withCount('childCategories')->findOrFail($id);
            $states = $this->buildCategoryStates(collect(), [$subcategory->category_id => collect([$subcategory])], []);
        } else {
            $childCategory = ChildCategory::query()->findOrFail($id);
            $states = $this->buildCategoryStates(collect(), [], [$childCategory->subcategory_id => collect([$childCategory])]);
        }

        return $states[$type][$id] ?? 'unchecked';
    }

    private function buildCategoryStates($categories, array $subcategoriesByCategory, array $childCategoriesBySubcategory): array
    {
        $selections = collect($this->categorySelections)
            ->filter(fn ($selection) => is_array($selection)
                && isset($selection['type'], $selection['id'])
                && is_numeric($selection['id'])
                && in_array($selection['type'], ['category', 'subcategory', 'child_category'], true));
        $subSelections = $selections
            ->where('type', 'subcategory')
            ->keyBy(fn ($selection) => (int) $selection['id'])
            ->all();
        $childSelections = $selections
            ->where('type', 'child_category')
            ->keyBy(fn ($selection) => (int) $selection['id'])
            ->all();
        $rootSelectionIds = $selections
            ->where('type', 'category')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $childIds = array_map('intval', array_keys($childSelections));
        $childSubcategoryById = $childIds
            ? ChildCategory::query()->whereIn('id', $childIds)->pluck('subcategory_id', 'id')->map(fn ($id) => (int) $id)->all()
            : [];
        $visibleSubcategoryIds = collect($subcategoriesByCategory)->flatten(1)->pluck('id')->map(fn ($id) => (int) $id)->all();
        $visibleChildSubcategoryIds = array_map('intval', array_keys($childCategoriesBySubcategory));
        $subcategoryIds = array_values(array_unique(array_merge(
            array_map('intval', array_keys($subSelections)),
            array_values($childSubcategoryById),
            $visibleSubcategoryIds,
            $visibleChildSubcategoryIds
        )));
        $subcategoryCategoryById = $subcategoryIds
            ? Subcategory::query()->whereIn('id', $subcategoryIds)->pluck('category_id', 'id')->map(fn ($id) => (int) $id)->all()
            : [];

        $childSelectionsBySubcategory = [];
        $rootHasIncludedDescendants = [];
        $rootHasExcludedDescendants = [];
        foreach ($subSelections as $subcategoryId => $selection) {
            $categoryId = $subcategoryCategoryById[$subcategoryId] ?? null;
            if ($categoryId) {
                $selection['excluded'] ??= false;
                $selection['excluded']
                    ? $rootHasExcludedDescendants[$categoryId] = true
                    : $rootHasIncludedDescendants[$categoryId] = true;
            }
        }

        foreach ($childSelections as $childId => $selection) {
            $subcategoryId = $childSubcategoryById[$childId] ?? null;
            if (!$subcategoryId) {
                continue;
            }

            $selection['excluded'] ??= false;
            $childSelectionsBySubcategory[$subcategoryId][] = $selection;
            $categoryId = $subcategoryCategoryById[$subcategoryId] ?? null;
            if ($categoryId) {
                $selection['excluded']
                    ? $rootHasExcludedDescendants[$categoryId] = true
                    : $rootHasIncludedDescendants[$categoryId] = true;
            }
        }

        $states = ['category' => [], 'subcategory' => [], 'child_category' => []];
        foreach ($categories as $category) {
            $isRootSelected = in_array((int) $category->id, $rootSelectionIds, true);
            $states['category'][$category->id] = $isRootSelected
                ? (!empty($rootHasExcludedDescendants[$category->id]) ? 'partial' : 'checked')
                : (!empty($rootHasIncludedDescendants[$category->id]) ? 'partial' : 'unchecked');
        }

        foreach (collect($subcategoriesByCategory)->flatten(1) as $subcategory) {
            $subcategoryId = (int) $subcategory->id;
            $categoryId = (int) $subcategory->category_id;
            $isRootSelected = in_array($categoryId, $rootSelectionIds, true);
            $hasDirectSelection = isset($subSelections[$subcategoryId]);
            $isSelected = $hasDirectSelection
                ? !(bool) ($subSelections[$subcategoryId]['excluded'] ?? false)
                : $isRootSelected;
            $childOverrides = $childSelectionsBySubcategory[$subcategoryId] ?? [];
            $relevantChildOverrides = collect($childOverrides)->filter(
                fn ($selection) => $isSelected
                    ? (bool) ($selection['excluded'] ?? false)
                    : !(bool) ($selection['excluded'] ?? false)
            );
            $allChildrenSelected = !$isSelected
                && (int) ($subcategory->child_categories_count ?? 0) > 0
                && $relevantChildOverrides->count() === (int) $subcategory->child_categories_count;
            $states['subcategory'][$subcategoryId] = $relevantChildOverrides->isNotEmpty()
                ? ($allChildrenSelected ? 'checked' : 'partial')
                : ($isSelected ? 'checked' : 'unchecked');
        }

        foreach (collect($childCategoriesBySubcategory)->flatten(1) as $childCategory) {
            $childId = (int) $childCategory->id;
            if (isset($childSelections[$childId])) {
                $states['child_category'][$childId] = (bool) ($childSelections[$childId]['excluded'] ?? false)
                    ? 'unchecked'
                    : 'checked';

                continue;
            }

            $subcategoryId = (int) $childCategory->subcategory_id;
            $subcategorySelection = $subSelections[$subcategoryId] ?? null;
            $states['child_category'][$childId] = $subcategorySelection
                ? (!(bool) ($subcategorySelection['excluded'] ?? false) ? 'checked' : 'unchecked')
                : (in_array($subcategoryCategoryById[$subcategoryId] ?? 0, $rootSelectionIds, true) ? 'checked' : 'unchecked');
        }

        return $states;
    }

    private function findCategorySelection(string $type, int $id): ?array
    {
        foreach ($this->categorySelections as $selection) {
            if (is_array($selection)
                && ($selection['type'] ?? null) === $type
                && (int) ($selection['id'] ?? 0) === $id) {
                return $selection;
            }
        }

        return null;
    }

    private function setCategorySelection(string $type, int $id, bool $excluded): void
    {
        foreach ($this->categorySelections as $index => $selection) {
            if (($selection['type'] ?? null) === $type && (int) ($selection['id'] ?? 0) === $id) {
                $this->categorySelections[$index]['excluded'] = $excluded;

                return;
            }
        }

        $this->categorySelections[] = ['type' => $type, 'id' => $id, 'excluded' => $excluded];
    }

    private function removeCategorySelection(string $type, int $id): void
    {
        $this->categorySelections = array_values(array_filter(
            $this->categorySelections,
            fn ($selection) => ($selection['type'] ?? null) !== $type || (int) ($selection['id'] ?? 0) !== $id
        ));
    }

    private function hasSelectedRootCategory(int $categoryId): bool
    {
        return $this->findCategorySelection('category', $categoryId) !== null;
    }

    private function removeDescendantSelectionsForCategory(int $categoryId): void
    {
        $selectedSubcategoryIds = collect($this->categorySelections)
            ->where('type', 'subcategory')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $selectedChildCategoryIds = collect($this->categorySelections)
            ->where('type', 'child_category')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $subcategoryIds = $selectedSubcategoryIds
            ? Subcategory::query()->where('category_id', $categoryId)->whereIn('id', $selectedSubcategoryIds)->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];
        $childCategoryIds = $selectedChildCategoryIds
            ? ChildCategory::query()->whereIn('id', $selectedChildCategoryIds)
                ->whereHas('subcategory', fn ($query) => $query->where('category_id', $categoryId))
                ->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        $this->categorySelections = array_values(array_filter($this->categorySelections, function ($selection) use ($categoryId, $subcategoryIds, $childCategoryIds): bool {
            if (($selection['type'] ?? null) === 'category' && (int) ($selection['id'] ?? 0) === $categoryId) {
                return false;
            }

            return !(($selection['type'] ?? null) === 'subcategory' && in_array((int) ($selection['id'] ?? 0), $subcategoryIds, true))
                && !(($selection['type'] ?? null) === 'child_category' && in_array((int) ($selection['id'] ?? 0), $childCategoryIds, true));
        }));
    }

    private function removeChildSelectionsForSubcategory(int $subcategoryId): void
    {
        $selectedChildCategoryIds = collect($this->categorySelections)
            ->where('type', 'child_category')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $childCategoryIds = $selectedChildCategoryIds
            ? ChildCategory::query()->where('subcategory_id', $subcategoryId)->whereIn('id', $selectedChildCategoryIds)->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        if ($childCategoryIds) {
            $this->categorySelections = array_values(array_filter(
                $this->categorySelections,
                fn ($selection) => ($selection['type'] ?? null) !== 'child_category'
                    || !in_array((int) ($selection['id'] ?? 0), $childCategoryIds, true)
            ));
        }
    }

    private function generateCode(): string
    {
        do {
            $code = 'TPL-' . Str::upper(Str::random(8));
        } while (Specification::query()->where('code', $code)->exists());

        return $code;
    }

    private function emptyPropertyForm(): array
    {
        return [
            'property_id' => null,
            'name_ar' => '',
            'name_en' => '',
            'input_type' => 'short_text',
            'column_span' => 6,
            'unit' => '',
            'group' => '',
            'merchant_guidance' => '',
            'is_required' => false,
            'show_in_summary' => false,
            'filterable' => false,
            'is_conditional' => false,
            'options_text' => '',
            'condition_field_key' => '',
            'condition_operator' => 'equals',
            'condition_value' => '',
        ];
    }

    private function ensurePropertyFieldKeys(): void
    {
        foreach ($this->properties as $index => $property) {
            if (empty($property['field_key'])) {
                $this->properties[$index]['field_key'] = (string) Str::uuid();
            }
        }
    }

    private function propertyInputTypes(): array
    {
        return [
            'short_text' => __('admin::home.specification_input_type_short_text'),
            'decimal' => __('admin::home.specification_input_type_decimal'),
            'integer' => __('admin::home.specification_input_type_integer'),
            'select' => __('admin::home.specification_input_type_select'),
            'multiselect' => __('admin::home.specification_input_type_multiselect'),
            'boolean' => __('admin::home.specification_input_type_boolean'),
            'long_text' => __('admin::home.specification_input_type_long_text'),
            'attachment' => __('admin::home.specification_input_type_attachment'),
            'number_range' => __('admin::home.specification_input_type_number_range'),
            'date' => __('admin::home.specification_input_type_date'),
        ];
    }

    private function conditionOperators(): array
    {
        return [
            'equals' => __('admin::home.specification_condition_equals'),
            'not_equals' => __('admin::home.specification_condition_not_equals'),
            'filled' => __('admin::home.specification_condition_filled'),
            'contains_option' => __('admin::home.specification_condition_contains_option'),
            'in_selected_values' => __('admin::home.specification_condition_in_selected_values'),
            'out_selected_values' => __('admin::home.specification_condition_out_selected_values'),
        ];
    }

    private function selectedSmallCategoryCount(): int
    {
        if ($this->activeTab !== 'categories' || $this->categorySelections === []) {
            return 0;
        }

        $rootIds = collect($this->categorySelections)
            ->where('type', 'category')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $includedSubcategoryIds = collect($this->categorySelections)
            ->where('type', 'subcategory')
            ->filter(fn ($selection) => !($selection['excluded'] ?? false))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $excludedSubcategoryIds = collect($this->categorySelections)
            ->where('type', 'subcategory')
            ->filter(fn ($selection) => (bool) ($selection['excluded'] ?? false))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $includedChildCategoryIds = collect($this->categorySelections)
            ->where('type', 'child_category')
            ->filter(fn ($selection) => !($selection['excluded'] ?? false))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $excludedChildCategoryIds = collect($this->categorySelections)
            ->where('type', 'child_category')
            ->filter(fn ($selection) => (bool) ($selection['excluded'] ?? false))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        if (!$rootIds && !$includedSubcategoryIds && !$includedChildCategoryIds) {
            return 0;
        }

        return ChildCategory::query()
            ->where(function ($query) use ($rootIds, $includedSubcategoryIds, $excludedSubcategoryIds, $includedChildCategoryIds, $excludedChildCategoryIds): void {
                if ($rootIds || $includedSubcategoryIds) {
                    $query->where(function ($includedBranches) use ($rootIds, $includedSubcategoryIds): void {
                        $includedBranches->whereHas('subcategory', function ($subcategories) use ($rootIds, $includedSubcategoryIds): void {
                            $subcategories->where(function ($selection) use ($rootIds, $includedSubcategoryIds): void {
                                if ($rootIds) {
                                    $selection->whereIn('category_id', $rootIds);
                                }
                                if ($includedSubcategoryIds) {
                                    $rootIds
                                        ? $selection->orWhereIn('id', $includedSubcategoryIds)
                                        : $selection->whereIn('id', $includedSubcategoryIds);
                                }
                            });
                        });
                    });

                    if ($excludedSubcategoryIds) {
                        $query->whereDoesntHave('subcategory', fn ($subcategories) => $subcategories->whereIn('id', $excludedSubcategoryIds));
                    }

                    if ($excludedChildCategoryIds) {
                        $query->whereNotIn('id', $excludedChildCategoryIds);
                    }
                }

                if ($includedChildCategoryIds) {
                    $query->orWhereIn('id', $includedChildCategoryIds);
                }
            })
            ->count();
    }

    private function authorizeCreate(): void
    {
        $admin = auth('admin')->user();
        if ($admin?->type === 'employee') {
            setPermissionsTeamId(1);
        }

        abort_unless(
            $admin && ($admin->type === 'admin' || $admin->hasAnyPermission(['create_specifications'])),
            403
        );
    }

    private function authorizeEdit(): void
    {
        $admin = auth('admin')->user();
        if ($admin?->type === 'employee') {
            setPermissionsTeamId(1);
        }

        abort_unless(
            $admin && ($admin->type === 'admin' || $admin->hasAnyPermission(['edit_specifications'])),
            403
        );
    }

    private function authorizeAccess(): void
    {
        if ($this->mode === 'edit') {
            $this->authorizeEdit();

            return;
        }

        $this->authorizeCreate();
    }
}
