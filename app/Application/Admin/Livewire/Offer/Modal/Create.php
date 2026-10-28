<?php

namespace Application\Admin\Livewire\Offer\Modal;

use App\Domain\AppBase\Actions\Offer\StoreOfferAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'inactive';
    public string $type = 'percent';
    public string $type_discount = "order";
    public string $name = "";
    public string $note = "new";
    public $usages_left;
    public $min_order_value;
    public $max_discount;
    public $discount_value;
    public $expired_at;
    public $limit_per_user;

    public $is_custom = false;
    public $period=1;

    protected function rules(): array
    {
        return [
            'name' => 'required|unique:offers,name',
            'note' => 'required',
            'usages_left' => 'required',
            'limit_per_user' => 'required',
            'min_order_value' => 'required',
            'max_discount' => 'required',
            'discount_value' => 'required',
            'period' => 'required',
            'type' => 'required|string|in:flat,percent',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',
            'type_discount' => 'required|string|in:order,delivery,total',
        ];
    }

    
    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function customHandle(){
        $this->is_custom = !$this->is_custom;
    }
  

    public function store(StoreOfferAction $storeOfferAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeOfferAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createOffer');
        $this->alert('success', 'Offer Created Successfully');
        $this->reset('name','note','usages_left','min_order_value', 'discount_value','max_discount','period','expired_at', 'type','status','limit_per_user','type_discount');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.offer.modal.create');
    }
}
