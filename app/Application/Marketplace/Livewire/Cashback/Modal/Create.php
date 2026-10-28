<?php

namespace Application\Marketplace\Livewire\Cashback\Modal;

use App\Domain\AppBase\Actions\CashBack\StoreCashBackAction;
use App\Domain\AppBase\Models\CashBack;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'inactive';
    public string $type = 'percent';
    public string $name = "";
    public $usages_left;
    public $min_order_value;
    public $max_discount;
    public $discount_value;
    public $expired_at;
    public $limit_per_user;

    public $is_custom = 1;
    public $merchant_id ;
    public $period=1;

    public function mount(){
        // $this->merchant_id = resolve('_InitialMerchant');
        $this->merchant_id = resolve('_InitialMerchant');
    }
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
            'expired_at' => 'required',
            'merchant_id'=>'',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',           
        ];
    }

    
    public function updateStatus($val): void
    {
        $this->status = $val;
    }


    public function store(StoreCashBackAction $storeCashbackAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        CashBack::create($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createMerchantCashback');
        $this->alert('success', __('admin::home.Cash Back Created Successfully'));
        $this->reset('name','usages_left','min_order_value', 'discount_value','max_discount','period','expired_at', 'type','status','limit_per_user');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.cashback.modal.create');
    }
}
