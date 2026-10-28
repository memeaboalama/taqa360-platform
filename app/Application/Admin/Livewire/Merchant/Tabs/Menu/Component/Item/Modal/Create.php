<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Item\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Item\StoreItemAction;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;


class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public $status = 'active',
        $title = ['en' => null,  'ar' => null],
        $image,
        $merchantId,
        $category_id,
        $description = ['en' => null,  'ar' => null],
        $default_price,
        $menu_id = null,
        $check_price = false,
        $calories, $preparing_time, $addition_group_ids = [], $addition_groups = [],$type_services =[],$item_prices=[]
        ;

    public $listeners = ['setCategoryId', 'createItem'];

    public function mount(int $merchantId): void
    {
        $this->addition_groups = \Domain\MarketPlace\Models\AdditionGroup::where(['merchant_id' => $merchantId, 'status' => StatusEnum::ACTIVE->value])
            ->get();

        $this->merchantId = $merchantId;
        $this->fullItemPrices();
    }
    
    public function fullItemPrices(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        $this->item_prices=[];
        foreach($this->type_services as $type_servicesL)
        {
            $this->item_prices[]=["item_id"=>"","type_service_id"=>$type_servicesL->id,"type_service_name"=>$type_servicesL->name,
            "price"=>'0',"status"=>'active'];
        }


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
    public function createItem($menu_id): void
    {
        $this->menu_id = $menu_id;
        $this->emit('openModal', '#createItem');
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
            'category_id' => 'required|exists:merchant_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|array',
            'description.*' => 'required|string|min:2|max:120',
            'default_price' => 'required|numeric',
            'calories' => 'required|numeric',
            'preparing_time' => 'required|numeric',
            "addition_group_ids" => "nullable|array",
            "addition_group_ids.*" => "nullable|exists:addition_groups,id",
            'item_prices' => 'array|required',
            'item_prices.*.type_service_id' => 'required',
            'item_prices.*.price' => 'required|numeric',
            'item_prices.*.status' => 'required',
            
        ];
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
            'category_id' => trans('admin::home.category'),
        ];
    }

    public function setCategoryId(int $category_id){
        $this->category_id = $category_id;
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreItemAction $storeItemAction): void
    {
        // $this->default_price=collect($this->item_prices)->where('type_service_id', '1')[0]["price"]??0;
        $data = $this->validate(attributes: $this->nickNames());
        \Illuminate\Support\Facades\Log ::debug((array)$data);
        DB::beginTransaction();
        $storeItemAction->handle($data ,$this->merchantId);
        $this->emit('closeModal', '#createItem');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->emit('refreshItems', $this->category_id, $this->menu_id);
        $this->reset('title', 'status', 'category_id', 'image', 'description', 'default_price', 'calories', 'preparing_time', 'addition_group_ids');
        $this->fullItemPrices();
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.item.modal.create');
    }
}
