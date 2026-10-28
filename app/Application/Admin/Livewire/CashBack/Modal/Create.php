<?php

namespace Application\Admin\Livewire\CashBack\Modal;

use App\Domain\AppBase\Actions\CashBack\StoreCashBackAction;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'inactive';
    public string $type = 'percent';
    public string $name = "";
    public $usages_left=1;
    public $min_order_value;
    public $max_discount;
    public $discount_value;
    public $expired_at;
    public $limit_per_user=1;

    public $is_custom = false;
    public $period=1;
    public $service_type_id=1;
    public $type_services=[];
    protected $listeners = ['selectSalesAgent'];
    protected function rules(): array
    {
        return [
            'name' => 'required|unique:cash_backs,name',
            'usages_left' => 'required',
            'limit_per_user' => 'required',
            'min_order_value' => 'required',
            'max_discount' => 'required',
            'discount_value' => 'required',
            'period' => 'required',
            'type' => 'required|string|in:flat,percent',
            'service_type_id' => 'required',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',
        ];
    }

    public function mount(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
    }
    public function updateStatus($val): void
    {
        $this->status = $val;
    }



    public function store(StoreCashBackAction $storeCashBackAction): void
    {
        $data = $this->validate();
        Log::debug($data);
        DB::beginTransaction();
        $storeCashBackAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCashBack');
        $this->alert('success', __('admin::home.Coupon Code Created Successfully'));
        $this->reset('name','usages_left','min_order_value', 'discount_value','max_discount','period','expired_at', 'type','service_type_id','status','limit_per_user');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.create');
    }
}
