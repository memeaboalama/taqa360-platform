<?php

namespace Application\Marketplace\Livewire\Product\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\StoreItemAction;
use App\Domain\Catalog\Models\Specification;
use App\Support\Enum\StatusEnum;
use App\Domain\MarketPlace\Models\Brand;
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

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public $status = 'active';
    public $title = ['en' => null, 'ar' => null];
    public $image;
    public $main_category_id;
    public $sub_category_id;
    public $child_category_id;
    public $brand_id;
    public $master_specification_ids = [];
    public $selected_master_specification_id;
    public $specification_values = [];
    public $specification_file;
    public $description = ['en' => null, 'ar' => null];
    public $default_price;
    public $stock;
    public $calories;
    public $preparing_time;
    public $merchant_id;
    public $merchantId;
    public $addition_group_ids = [];
    public $additional_images = [];
    public $categories;
    public $subcategories;
    public $childCategories;
    public $brands;
    public $masterSpecifications;
    public $addition_groups;

    public $item_prices = [];
    public $type_services;

    public $listeners = ['createProduct', 'setMerchantId'];

    public function mount(?int $merchantId = null): void
    {
        $this->merchantId = $merchantId;
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->brands = collect();
        $this->masterSpecifications = collect();
        $this->addition_groups = AdditionGroup::where('status', StatusEnum::ACTIVE->value)
            ->where('merchant_id', $this->merchantId ?? auth('merchant')->id())
            ->get();
        $this->fullItemPrices();
    }

    public function fullItemPrices(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' => 'active'])->get();
        $this->item_prices = [];
        foreach($this->type_services as $type_service) {
            $this->item_prices[] = [
                "item_id" => "",
                "type_service_id" => $type_service->id,
                "type_service_name" => $type_service->name,
                "price" => '0',
                "status" => 'active'
            ];
        }
    }

    public function updateStatusRow($index): void
    {
        if ($this->item_prices[$index]['status'] == "active") {
            $this->item_prices[$index]['status'] = "inactive";
        } else {
            $this->item_prices[$index]['status'] = "active";
        }
    }

    public function createProduct($merchant_id = null): void
    {
        if ($merchant_id !== null) {
            $this->merchantId = (int) $merchant_id;
            $this->merchant_id = $this->merchantId;
        }
        $this->emit('openModal', '#createProduct');
    }

    public function setMerchantId($merchant_id): void
    {
        $this->merchantId = (int) $merchant_id;
        $this->merchant_id = $this->merchantId;
        $this->addition_groups = AdditionGroup::where('status', StatusEnum::ACTIVE->value)
            ->where('merchant_id', $this->merchantId)
            ->get();
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
            'brand_id' => [
                'nullable',
                Rule::exists('brands', 'id')->where('is_active', true)->whereNull('deleted_at'),
                Rule::exists('brand_category', 'brand_id')->where('category_id', $this->main_category_id),
            ],
            'master_specification_ids' => 'nullable|array',
            'selected_master_specification_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail): void {
                    if ($value && !$this->isMasterSpecificationAvailable((int) $value)) {
                        $fail(__('admin::home.specification_not_available_for_category'));
                    }
                },
            ],
            'master_specification_ids.*' => [
                'integer',
                'distinct',
                function ($attribute, $value, $fail): void {
                    if (!$this->isMasterSpecificationAvailable((int) $value)) {
                        $fail(__('admin::home.specification_not_available_for_category'));
                    }
                },
            ],
            'specification_values' => 'nullable|array',
            'specification_values.*' => 'nullable|string|max:5000',
            'specification_file' => 'nullable|file|mimes:pdf|max:10240',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|array',
            'description.*' => 'required|string|min:10',
            'default_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'calories' => 'nullable|numeric|min:0',
            'preparing_time' => 'nullable|numeric|min:0',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'addition_group_ids' => 'nullable|array',
            'addition_group_ids.*' => 'nullable|exists:addition_groups,id',
            'item_prices' => 'array|required',
            'item_prices.*.type_service_id' => 'required',
            'item_prices.*.price' => 'required|numeric|min:0',
            'item_prices.*.status' => 'required',
        ];
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
            'brand_id' => __('admin::main.fields.brand'),
            'merchant_id' => __('admin::main.fields.merchant'),
        ];
    }

    public function updatedMainCategoryId($value): void
    {
        $this->subcategories = Subcategory::where('category_id', $value)
            ->where('is_active', true)
            ->get();
        $this->brands = Brand::query()
            ->where('is_active', true)
            ->whereHas('categories', fn ($query) => $query->where('categories.id', $value))
            ->orderBy('id')
            ->get();
        $this->brand_id = null;
        $this->master_specification_ids = [];
        $this->selected_master_specification_id = null;
        $this->specification_values = [];
        $this->sub_category_id = null;
        $this->child_category_id = null;
        $this->childCategories = collect();
        $this->loadMasterSpecificationsForSelectedCategories();
    }

    public function updatedSubCategoryId($value): void
    {
        $this->childCategories = ChildCategory::where('subcategory_id', $value)
            ->where('is_active', true)
            ->get();
        $this->child_category_id = null;
        $this->loadMasterSpecificationsForSelectedCategories();
    }

    public function updatedChildCategoryId(): void
    {
        $this->loadMasterSpecificationsForSelectedCategories();
    }

    public function addMasterSpecification(): void
    {
        $this->validateOnly('selected_master_specification_id');

        if ($this->selected_master_specification_id === null || $this->selected_master_specification_id === '') {
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
    }

    private function loadMasterSpecificationsForSelectedCategories(): void
    {
        if (!$this->main_category_id) {
            $this->masterSpecifications = collect();

            return;
        }

        $selectedIds = array_map('intval', $this->master_specification_ids);
        $this->masterSpecifications = Specification::query()
            ->isMaster()
            ->where('status', StatusEnum::ACTIVE->value)
            ->forCatalogCategories(
                (int) $this->main_category_id,
                $this->sub_category_id ? (int) $this->sub_category_id : null,
                $this->child_category_id ? (int) $this->child_category_id : null
            )
            ->with(['properties' => fn ($query) => $query
                ->whereNull('item_id')
                ->where('status', StatusEnum::ACTIVE->value)])
            ->orderBy('id')
            ->get();

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

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreItemAction $action): void
    {
        $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $itemPrices = array_map(function (array $itemPrice): array {
                if ((float) $itemPrice['price'] === 0.0) {
                    $itemPrice['price'] = $this->default_price;
                }

                return $itemPrice;
            }, $this->item_prices);

            $data = [
                'status' => $this->status,
                'main_category_id' => $this->main_category_id,
                'sub_category_id' => $this->sub_category_id,
                'child_category_id' => $this->child_category_id,
                'brand_id' => $this->brand_id,
                'master_specification_ids' => $this->master_specification_ids,
                'specification_values' => $this->specification_values,
                'specification_file' => $this->specification_file,
                'image' => $this->image,
                'default_price' => $this->default_price,
                'stock' => $this->stock === '' || $this->stock === null ? null : (int) $this->stock,
                'calories' => $this->calories ?? 0.00,
                'preparing_time' => $this->preparing_time ?? 0,
                'additional_images' => $this->additional_images,
                'addition_group_ids' => $this->addition_group_ids,
                'title' => $this->title,
                'description' => $this->description,
                'item_prices' => $itemPrices,
            ];

            if (!$action->handle($data, $this->merchantId)) {
                throw new \RuntimeException('Product creation failed.');
            }
            $this->emit('closeModal', '#createProduct');
            $this->emit('refreshProducts');
            $this->alert('success', __('admin::main.messages.product_created'));
            $this->resetForm();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Product Error: ' . $e->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'title', 'status', 'main_category_id', 'sub_category_id', 'child_category_id',
            'brand_id', 'master_specification_ids', 'selected_master_specification_id',
            'specification_values', 'specification_file', 'merchant_id', 'image', 'description',
            'default_price', 'stock', 'calories', 'preparing_time', 'addition_group_ids', 'additional_images'
        ]);
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->brands = collect();
        $this->masterSpecifications = collect();
        $this->selected_master_specification_id = null;
        $this->fullItemPrices();
    }

    public function removeAdditionalImage($index): void
    {
        if (isset($this->additional_images[$index])) {
            unset($this->additional_images[$index]);
            $this->additional_images = array_values($this->additional_images);
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.product.modal.create');
    }
}
