<?php

namespace Application\Marketplace\Livewire\Product\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Livewire\Component;

class Show extends Component
{
    public $productId;
    public $merchantId;
    public $title = [];
    public $description = [];
    public $main_category;
    public $sub_category;
    public $child_category;
    public $default_price;
    public $stock;
    public $calories;
    public $preparing_time;
    public $status;
    public $image_path;
    public $existing_files = [];
    public $item_prices = [];
    public $technical_specifications = [];
    public $specification_file;

    protected $listeners = ['viewProduct'];

    public function mount(?int $merchantId = null): void
    {
        $this->merchantId = $merchantId;
    }

    public function viewProduct($id, FetchSingleItemAction $action): void
    {
        $product = $action->handle($id, $this->merchantId);

        if (!$product) {
            return;
        }

        $product->load(['catalogSpecifications.properties', 'files']);
        $this->productId = $product->id;
        $this->title = $product->getTranslations('title');
        $this->description = $product->getTranslations('description');
        $this->default_price = $product->default_price;
        $this->stock = $product->stock;
        $this->calories = $product->calories;
        $this->preparing_time = $product->preparing_time;
        $this->status = $product->status?->value ?? $product->status;
        $this->image_path = $product->image_path;

        $this->main_category = optional($product->mainCategory)->title;
        $this->sub_category = optional($product->subCategory)->title;
        $this->child_category = optional($product->childCategory)->title;

        if ($this->sub_category === null && $product->sub_category_id) {
            $this->sub_category = optional(Subcategory::find($product->sub_category_id))->title;
        }
        if ($this->child_category === null && $product->child_category_id) {
            $this->child_category = optional(ChildCategory::find($product->child_category_id))->title;
        }

        $this->loadItemPrices($product);
        $this->loadExistingFiles($product);
        $this->loadTechnicalSpecifications($product);

        $this->emit('openModal', '#showProduct');
    }

    private function loadItemPrices($product): void
    {
        $typeServices = \App\Domain\AppBase\Models\TypeService::where('status', 'active')->get();
        $existingPrices = $product->itemPrices->keyBy('type_service_id');

        $this->item_prices = [];
        foreach ($typeServices as $typeService) {
            $existingPrice = $existingPrices->get($typeService->id);
            $this->item_prices[] = [
                "type_service_name" => $typeService->name,
                "price" => $existingPrice ? $existingPrice->price : null,
                "status" => $existingPrice ? $existingPrice->status : null,
            ];
        }
    }

    private function loadExistingFiles($product): void
    {
        $this->existing_files = $product->files
            ->where('collection_name', 'item_additional_image')
            ->map(function ($file) {
            return [
                'url' => $file->url,
                'original_name' => $file->original_name,
            ];
        })->toArray();
    }

    private function loadTechnicalSpecifications($product): void
    {
        $this->technical_specifications = $product->catalogSpecifications
            ->map(function ($specification) {
                return [
                    'name' => $specification->name,
                    'properties' => $specification->properties
                        ->filter(fn ($property) => filled($property->value))
                        ->map(fn ($property) => [
                            'name' => $property->name,
                            'value' => $property->value,
                        ])
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn ($specification) => !empty($specification['properties']))
            ->values()
            ->all();

        $file = $product->files->firstWhere('collection_name', 'item_specification');
        $this->specification_file = $file ? [
            'url' => $file->url,
            'original_name' => $file->original_name,
        ] : null;
    }

    public function render()
    {
        return view('marketplace::livewire.product.modal.show');
    }
}