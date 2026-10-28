<?php

namespace Application\Marketplace\Livewire\Menu\Component\Item\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use Livewire\Component;

class Show extends Component
{
    public $status = 'active',
        $title = ['en' => null,  'ar' => null],
        $category_id,
        $description = ['en' => null,  'ar' => null],
        $default_price,
        $calories,
        $preparing_time,
        $model_id,
        $menu_id = null,
        $item_prices = [],
        $addition_groups = [],
        $image_path,
        $existing_images = [];

    public $merchantId;

    protected $listeners = ['showItem'];

    public function mount(): void
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function showItem($id, $menu_id, FetchSingleItemAction $action): void
    {
        $model = $action->handle($id, $this->merchantId);
        $this->menu_id = $menu_id;
        if ($model) {
            $this->title = $model->getTranslations('title');
            $this->description = $model->getTranslations('description');
            $this->default_price = $model->default_price;
            $this->calories = $model->calories;
            $this->preparing_time = $model->preparing_time;
            $this->category_id = $model->category_id;
            $this->status = $model->status->value;
            $this->model_id = $model->id;
            $this->image_path = $model->image_path;
            $this->item_prices = $model->itemPrices->map(function ($price) {
                return [
                    'type_service_id' => $price->type_service_id,
                    'type_service_name' => $price->type_service_name,
                    'price' => $price->price,
                    'status' => $price->status?->value,
                ];
            })->values()->toArray();
            $this->existing_images = $model->files()->get()->map(function ($file) {
                return ['id' => $file->id, 'url' => $file->url];
            })->toArray();
            $this->addition_groups = $model->additionGroups()->get();
            $this->emit('openModal', '#showItem');
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.item.modal.show');
    }
}
