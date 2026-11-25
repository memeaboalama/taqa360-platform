<?php

namespace Application\Marketplace\Livewire\Product\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\UpdateItemAction;
use App\Domain\Catalog\Models\Specification;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\AdditionGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public $status = 'active';
    public $title = ['en' => null, 'ar' => null];
    public $image;
    public $main_category_id;
    public $sub_category_id;
    public $child_category_id;
    public $master_specification_ids = [];
    public $selected_master_specification_id;
    public $specification_values = [];
    public $specification_file;
    public $existing_specification_file;
    public $remove_specification_file = false;
    public $description = ['en' => null, 'ar' => null];
    public $default_price;
    public $stock;
    public $calories;
    public $preparing_time;
    public $merchant_id;
    public $merchantId;
    public $addition_group_ids = [];
    public $additional_images = [];
    public $product_id;
    public $image_path;

    public $categories;
    public $subcategories;
    public $childCategories;
    public $masterSpecifications;
    public $addition_groups;

    public $item_prices = [];
    public $type_services;
    public $existing_files = [];

    protected $listeners = ['updateProduct'];

    public function mount(?int $merchantId = null): void
    {
        $this->merchantId = $merchantId;
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->masterSpecifications = collect();
        $this->addition_groups = AdditionGroup::where('status', StatusEnum::ACTIVE->value)
            ->where('merchant_id', $this->merchantId ?? auth('merchant')->id())
            ->get();
    }

    public function updateProduct($id, FetchSingleItemAction $action): void
    {
        $product = $action->handle($id, $this->merchantId);

        if ($product) {
            $product->load(['catalogSpecifications.properties', 'files']);
            $this->product_id = $product->id;
            $this->title = $product->getTranslations('title');
            $this->description = $product->getTranslations('description');
            $this->default_price = $product->default_price;
            $this->stock = $product->stock;
            $this->calories = $product->calories;
            $this->preparing_time = $product->preparing_time;
            $this->main_category_id = $product->main_category_id;
            $this->sub_category_id = $product->sub_category_id;
            $this->child_category_id = $product->child_category_id;
            $this->merchant_id = $product->merchant_id;
            $this->status = $product->status->value;
            $this->image_path = $product->image_path;

            $this->addition_group_ids = $product->additionGroups()->pluck('addition_group_id')->toArray();

            $this->loadItemPrices($product);
            $this->loadExistingFiles($product);
            $this->loadExistingMasterSpecifications($product);
            $this->loadExistingSpecificationFile($product);
            $this->loadCategoriesBasedOnCurrentValues();

            $this->productLoaded();
            return;
        }

        $this->alert('error', __('admin::main.messages.something_went_wrong'));
    }

    private function loadItemPrices($product): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where('status', 'active')->get();
        $existingPrices = $product->itemPrices->keyBy('type_service_id');

        $this->item_prices = [];
        foreach ($this->type_services as $type_service) {
            $existingPrice = $existingPrices->get($type_service->id);

            $this->item_prices[] = [
                "type_service_id" => $type_service->id,
                "type_service_name" => $type_service->name,
                "price" => $existingPrice ? $existingPrice->price : '0',
                "status" => $existingPrice?->status?->value ?? 'inactive'
            ];
        }
    }

    protected function productLoaded(): void
    {
        $this->emit('openModal', '#updateProduct');
    }

    private function loadExistingFiles($product): void
    {
        $this->existing_files = $product->files
            ->where('collection_name', 'item_additional_image')
            ->map(function($file) {
            return [
                'id' => $file->id,
                'url' => $file->url,
                'path' => $file->path,
                'file_name' => $file->file_name,
                'original_name' => $file->original_name
            ];
        })->toArray();
    }

    private function loadExistingMasterSpecifications($product): void
    {
        $this->master_specification_ids = [];
        $this->specification_values = [];

        foreach ($product->catalogSpecifications as $specification) {
            $masterSpecificationId = $specification->meta['master_specification_id'] ?? null;
            if (!$masterSpecificationId) {
                continue;
            }

            $this->master_specification_ids[] = (int) $masterSpecificationId;
            foreach ($specification->properties as $property) {
                $masterPropertyId = $property->meta['master_property_id'] ?? null;
                if ($masterPropertyId) {
                    $this->specification_values[$masterPropertyId] = $property->value;
                }
            }
        }

        $this->master_specification_ids = array_values(array_unique($this->master_specification_ids));
    }

    private function loadExistingSpecificationFile($product): void
    {
        $file = $product->files->firstWhere('collection_name', 'item_specification');
        $this->existing_specification_file = $file ? [
            'url' => $file->url,
            'original_name' => $file->original_name,
        ] : null;
        $this->remove_specification_file = false;
    }

    private function loadCategoriesBasedOnCurrentValues(): void
    {
        if ($this->main_category_id) {
            $this->subcategories = Subcategory::where('category_id', $this->main_category_id)
                ->where('is_active', true)
                ->get();
        }

        if ($this->sub_category_id) {
            $this->childCategories = ChildCategory::where('subcategory_id', $this->sub_category_id)
                ->where('is_active', true)
                ->get();
        }

        $this->loadMasterSpecificationsForCategory(
            $this->main_category_id,
            $this->master_specification_ids,
            $this->sub_category_id,
            $this->child_category_id
        );
    }

    private function loadMasterSpecificationsForCategory($categoryId, array $includeIds = [], $subcategoryId = null, $childCategoryId = null): void
    {
        if (!$categoryId) {
            $this->masterSpecifications = collect();
            return;
        }

        $this->masterSpecifications = Specification::query()
            ->isMaster()
            ->where(function ($query) use ($categoryId, $subcategoryId, $childCategoryId, $includeIds) {
                $query->where(function ($categoryQuery) use ($categoryId, $subcategoryId, $childCategoryId) {
                    $categoryQuery->where('status', StatusEnum::ACTIVE->value)
                        ->forCatalogCategories(
                            (int) $categoryId,
                            $subcategoryId ? (int) $subcategoryId : null,
                            $childCategoryId ? (int) $childCategoryId : null
                        );
                });

                if ($includeIds) {
                    $query->orWhereIn('id', $includeIds);
                }
            })
            ->with(['properties' => fn ($query) => $query
                ->whereNull('item_id')
                ->where('status', StatusEnum::ACTIVE->value)])
            ->orderBy('id')
            ->get();
    }

    public function updatedMainCategoryId($value): void
    {
        $this->subcategories = Subcategory::where('category_id', $value)
            ->where('is_active', true)
            ->get();
        $this->sub_category_id = null;
        $this->child_category_id = null;
        $this->childCategories = collect();
        $this->master_specification_ids = [];
        $this->selected_master_specification_id = null;
        $this->specification_values = [];
        $this->loadMasterSpecificationsForCategory($value);
    }

    public function updatedSubCategoryId($value): void
    {
        $this->childCategories = ChildCategory::where('subcategory_id', $value)
            ->where('is_active', true)
            ->get();
        $this->child_category_id = null;
        $this->refreshMasterSpecificationsForSelection($value, null);
    }

    public function updatedChildCategoryId(): void
    {
        $this->refreshMasterSpecificationsForSelection($this->sub_category_id, $this->child_category_id);
    }

    private function refreshMasterSpecificationsForSelection($subcategoryId, $childCategoryId): void
    {
        $selectedIds = array_map('intval', $this->master_specification_ids);
        $this->loadMasterSpecificationsForCategory(
            $this->main_category_id,
            [],
            $subcategoryId,
            $childCategoryId
        );
        $availableIds = $this->masterSpecifications->modelKeys();
        $this->master_specification_ids = array_values(array_intersect($selectedIds, $availableIds));
        if ($this->selected_master_specification_id
            && !in_array((int) $this->selected_master_specification_id, $availableIds, true)) {
            $this->selected_master_specification_id = null;
        }
        $availablePropertyIds = $this->masterSpecifications
            ->whereIn('id', $this->master_specification_ids)
            ->flatMap(fn ($specification) => $specification->properties)
            ->pluck('id')
            ->all();
        $this->specification_values = array_intersect_key(
            $this->specification_values,
            array_fill_keys($availablePropertyIds, true)
        );
    }

    public function updateStatusRow($index): void
    {
        if ($this->item_prices[$index]['status'] == "active") {
            $this->item_prices[$index]['status'] = "inactive";
        } else {
            $this->item_prices[$index]['status'] = "active";
        }
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function addMasterSpecification(): void
    {
        $this->validateOnly('selected_master_specification_id');

        if (!$this->selected_master_specification_id) {
            return;
        }

        $id = (int) $this->selected_master_specification_id;
        if (!in_array($id, array_map('intval', $this->master_specification_ids), true)) {
            $this->master_specification_ids[] = $id;
        }

        $this->selected_master_specification_id = null;
    }

    public function removeMasterSpecification(int $id): void
    {
        $this->master_specification_ids = array_values(array_filter(
            $this->master_specification_ids,
            fn ($selectedId) => (int) $selectedId !== $id
        ));

        $specification = $this->masterSpecifications->firstWhere('id', $id);
        foreach ($specification?->properties ?? [] as $property) {
            unset($this->specification_values[$property->id]);
        }
    }

    public function removeSpecificationFile(): void
    {
        $this->existing_specification_file = null;
        $this->remove_specification_file = true;
    }

    public function removeExistingFile($index): void
    {
        if (isset($this->existing_files[$index])) {
            $fileId = $this->existing_files[$index]['id'];
            unset($this->existing_files[$index]);
            $this->existing_files = array_values($this->existing_files);
        }
    }

    public function removeAdditionalImage($index): void
    {
        if (isset($this->additional_images[$index])) {
            unset($this->additional_images[$index]);
            $this->additional_images = array_values($this->additional_images);
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
            'main_category_id' => 'required|exists:categories,id',
            'sub_category_id' => [
                'nullable',
                Rule::exists('sub_categories', 'id')->where('category_id', $this->main_category_id),
            ],
            'child_category_id' => [
                'nullable',
                Rule::exists('child_categories', 'id')->where('subcategory_id', $this->sub_category_id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|array',
            'description.*' => 'required|string|min:10',
            'default_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'master_specification_ids' => 'nullable|array',
            'master_specification_ids.*' => [
                'integer',
                'distinct',
                function ($attribute, $value, $fail): void {
                    if (!$this->isMasterSpecificationAvailable((int) $value)) {
                        $fail(__('admin::home.specification_not_available_for_category'));
                    }
                },
            ],
            'selected_master_specification_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail): void {
                    if ($value && !$this->isMasterSpecificationAvailable((int) $value)) {
                        $fail(__('admin::home.specification_not_available_for_category'));
                    }
                },
            ],
            'specification_values' => 'nullable|array',
            'specification_values.*' => 'nullable|string|max:5000',
            'specification_file' => 'nullable|file|mimes:pdf|max:10240',
            'calories' => 'nullable|numeric|min:0',
            'preparing_time' => 'nullable|numeric|min:0',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'addition_group_ids' => 'nullable|array',
            'addition_group_ids.*' => 'nullable|exists:addition_groups,id',
            'item_prices' => 'array|required',
            'item_prices.*.type_service_id' => 'required',
            'item_prices.*.price' => 'required|numeric',
            'item_prices.*.status' => 'required',
        ];
    }

    private function isMasterSpecificationAvailable(int $id): bool
    {
        return Specification::query()
            ->isMaster()
            ->where('status', StatusEnum::ACTIVE->value)
            ->forCatalogCategories(
                $this->main_category_id ? (int) $this->main_category_id : null,
                $this->sub_category_id ? (int) $this->sub_category_id : null,
                $this->child_category_id ? (int) $this->child_category_id : null
            )
            ->whereKey($id)
            ->exists();
    }

    public function nickNames()
    {
        return [
            'title.ar' => __('admin::main.fields.title'),
            'title.en' => __('admin::main.fields.title'),
            'description.ar' => __('admin::main.fields.description'),
            'description.en' => __('admin::main.fields.description'),
            'default_price' => __('admin::main.fields.default_price'),
            'stock' => __('admin::main.fields.stock'),
            'calories' => __('admin::main.fields.calories'),
            'preparing_time' => __('admin::main.fields.preparing_time'),
            'main_category_id' => __('admin::main.fields.category'),
            'sub_category_id' => __('admin::main.fields.subcategory'),
            'child_category_id' => __('admin::main.fields.child_category'),
            'merchant_id' => __('admin::main.fields.merchant'),
        ];
    }

    public function store(UpdateItemAction $action): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $data['existing_files'] = $this->existing_files;
            $data['master_specification_ids'] = $this->master_specification_ids;
            $data['specification_values'] = $this->specification_values;
            $data['specification_file'] = $this->specification_file;
            $data['remove_specification_file'] = $this->remove_specification_file;
            $data = $this->prepareUpdateData($data);
            if (!$action->handle($this->product_id, $data, $this->merchantId)) {
                throw new \RuntimeException('Product update failed.');
            }
            DB::commit();
            $this->productUpdated();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Product Error: ' . $e->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    protected function prepareUpdateData(array $data): array
    {
        return $data;
    }

    protected function productUpdated(): void
    {
        $this->emit('closeModal', '#updateProduct');
        $this->emit('refreshProducts');
        $this->alert('success', __('admin::main.messages.product_updated'));
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'title', 'status', 'main_category_id', 'sub_category_id', 'child_category_id',
            'merchant_id', 'image', 'description', 'default_price', 'calories',
            'preparing_time', 'addition_group_ids', 'additional_images',
            'master_specification_ids', 'selected_master_specification_id',
            'specification_values', 'specification_file', 'existing_specification_file',
            'remove_specification_file'
        ]);
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->masterSpecifications = collect();
        $this->existing_files = [];
        $this->image_path = null;
    }

    public function render()
    {
        return view('marketplace::livewire.product.modal.update');
    }
}
