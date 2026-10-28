<?php

namespace Application\Marketplace\Livewire\Product\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\UpdateItemAction;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\AdditionGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public $description = ['en' => null, 'ar' => null];
    public $default_price;
    public $stock;
    public $calories;
    public $preparing_time;
    public $merchant_id;
    public $addition_group_ids = [];
    public $additional_images = [];
    public $product_id;
    public $image_path;

    public $categories;
    public $subcategories;
    public $childCategories;
    public $addition_groups;

    public $item_prices = [];
    public $type_services;
    public $existing_files = [];

    protected $listeners = ['updateProduct'];

    public function mount(): void
    {
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->addition_groups = AdditionGroup::where('status', StatusEnum::ACTIVE->value)->get();
    }

    public function updateProduct($id, FetchSingleItemAction $action): void
    {
        $product = $action->handle($id);

        if ($product) {
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
            $this->loadCategoriesBasedOnCurrentValues();

            $this->emit('openModal', '#updateProduct');
        }
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
                "status" => $existingPrice ? $existingPrice->status : 'inactive'
            ];
        }
    }

    private function loadExistingFiles($product): void
    {
        $this->existing_files = $product->files->map(function($file) {
            return [
                'id' => $file->id,
                'url' => $file->url,
                'path' => $file->path,
                'file_name' => $file->file_name,
                'original_name' => $file->original_name
            ];
        })->toArray();
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
    }

    public function updatedMainCategoryId($value): void
    {
        $this->subcategories = Subcategory::where('category_id', $value)
            ->where('is_active', true)
            ->get();
        $this->sub_category_id = null;
        $this->child_category_id = null;
        $this->childCategories = collect();
    }

    public function updatedSubCategoryId($value): void
    {
        $this->childCategories = ChildCategory::where('subcategory_id', $value)
            ->where('is_active', true)
            ->get();
        $this->child_category_id = null;
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
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'item_prices.*.price' => 'required|numeric',
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
            'merchant_id' => __('admin::main.fields.merchant'),
        ];
    }

    public function store(UpdateItemAction $action): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $data['existing_files'] = $this->existing_files;
            $action->handle($this->product_id, $data);
            $this->emit('closeModal', '#updateProduct');
            $this->emit('refreshProducts');
            $this->alert('success', __('admin::main.messages.product_updated'));
            $this->resetForm();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Product Error: ' . $e->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'title', 'status', 'main_category_id', 'sub_category_id', 'child_category_id',
            'merchant_id', 'image', 'description', 'default_price', 'calories',
            'preparing_time', 'addition_group_ids', 'additional_images'
        ]);
        $this->categories = Category::where('is_active', true)->get();
        $this->subcategories = collect();
        $this->childCategories = collect();
        $this->existing_files = [];
        $this->image_path = null;
    }

    public function render()
    {
        return view('marketplace::livewire.product.modal.update');
    }
}
