<?php

namespace Application\Marketplace\Livewire\Menu\Component\Item\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\UpdateItemAction;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;


class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public $status = 'active',
        $title = ['en' => null,  'ar' => null],
        $image = "",
        $category_id,
        $description = ['en' => null,  'ar' => null],
        $default_price,
        $calories,
        $preparing_time,
        $model_id,
        $menu_id = null,
        $item_prices=[],
        $item_prices_in_DB=[],
        $addition_group_ids = [],
        $addition_groups = [],
        $image_path
        ,$type_services=[]
        ,$additional_images=[]
        ,$existing_images=[]
        ,$existing_files=[]
        ;
    public $merchantId;


    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
            'category_id' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'description' => 'required|array',
            'description.*' => 'nullable|string|min:0',
            'default_price' => 'required|numeric',
            'calories' => 'nullable|numeric',
            'preparing_time' => 'nullable|numeric',
            "addition_group_ids" => "nullable|array",
            "addition_group_ids.*" => "nullable|exists:addition_groups,id",
            'item_prices' => 'array|required',
            'item_prices.*.type_service_id' => 'required',
            'item_prices.*.price' => 'required|numeric',
            'item_prices.*.status' => 'required',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_files' => 'nullable|array',

        ];
    }
    protected $listeners = ['updateItem'];
    public function mount(): void
    {

        $this->addition_groups = \Domain\MarketPlace\Models\AdditionGroup::where(['merchant_id' => resolve('_InitialMerchant'), 'status' => StatusEnum::ACTIVE->value])->get();
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'description.en' => trans('admin::home.Description'),
            'description.ar' => trans('admin::home.Description'),
            'default_price' => trans('admin::home.Default Price'),
            'calories' => trans('admin::home.calories'),
            'preparing_time' => trans('admin::home.preparing_time'),
        ];
    }
    public function fullItemPrices(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        $item_pricesNew=[];
        foreach($this->type_services as $type_servicesL)
        {
            $item_pricesNew[]=["type_service_id"=>$type_servicesL->id,"type_service_name"=>$type_servicesL->name,
            "price"=>'0',"status"=>'inactive'];
        }
        $this->item_prices = collect($this->item_prices_in_DB)->merge($item_pricesNew)
    ->unique('type_service_id')
    ->values() 
    ->toArray();
    }
    public function updateStatusRow($indx): void
    {
        if( $this->item_prices[$indx]['status']== "active" )
       {
        $this->item_prices[$indx]['status']="inactive";
       } else
       {
        $this->item_prices[$indx]['status']="active";
       }
    }
    public function updateItem($id, $menu_id, FetchSingleItemAction $action): void
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
            $this->addition_group_ids = $model->additionGroups()->pluck('addition_group_id')->toArray();
            $this->status = $model->status->value;
            $this->model_id = $model->id;
            $this->image_path = $model->image_path;
            $this->additional_images = [];
            $this->existing_images = $model->files()->get()->map(function ($file) {
                return ['id' => $file->id, 'url' => $file->url];
            })->toArray();
            $this->existing_files = collect($this->existing_images)->map(fn($img) => ['id' => $img['id']])->toArray();
            $this->item_prices_in_DB=$model->itemPrices;
            $this->fullItemPrices();
            $this->emit('openModal', '#updateItem');
            
        }
    }




    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function removeAdditionalImage($index): void
    {
        if (isset($this->additional_images[$index])) {
            unset($this->additional_images[$index]);
            $this->additional_images = array_values($this->additional_images);
        }
    }

    public function removeExistingImage($fileId): void
    {
        $this->existing_files = collect($this->existing_files)->filter(function ($file) use ($fileId) {
            return (int) $file['id'] !== (int) $fileId;
        })->values()->toArray();

        $this->existing_images = collect($this->existing_images)->filter(function ($img) use ($fileId) {
            return (int) $img['id'] !== (int) $fileId;
        })->values()->toArray();
    }

    public function store(UpdateItemAction $updateItemAction): void
    {
        // $this->default_price=collect($this->item_prices)->where('type_service_id', 1)[0]["price"]??0;
        $data = $this->validate(attributes: $this->nickNames());
        $data['additional_images'] = $this->additional_images;
        $data['existing_files'] = $this->existing_files;

        DB::beginTransaction();
        DB::table('item_prices')->where(['item_id' => $this->model_id])->delete();
        $updateItemAction->handle($this->model_id, $data);
        $this->emit('closeModal', '#updateItem');
        $this->emit('refreshDatatable');
        $this->emit('refreshItems', $this->category_id, $this->menu_id);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));

        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.item.modal.update');
    }
}
