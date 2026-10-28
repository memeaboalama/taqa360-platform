<?php

namespace Application\Admin\Livewire\CashBack\Modal\Merchants;

use App\Domain\AppBase\Actions\CashBack\StoreCashBackAction;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $type = 'percent';
    public string $type_discount = "order";
    public string $code = "";
    public $usages_left;
    public $min_order_value;
    public $max_discount;
    public $discount_value;
    public $expired_at;
    public $limit_per_user;

    public $is_custom = 1;
    public $sales_agent_id;
    public $sales_agent_name;
    public $merchant_id ;

    protected $listeners = ['createCashBackAdminMerchants' ,'selectSalesAgent'];

    public function createCashBackAdminMerchants($id): void
    {
        $this->merchant_id = $id;
        $this->emit('openModal', '#createCashBackAdminMerchants');
    }
    protected function rules(): array
    {
        return [
            'code' => 'required|unique:CashBacks,code',
            'usages_left' => 'required',
            'limit_per_user' => 'required',
            'min_order_value' => 'required',
            'max_discount' => 'required',
            'discount_value' => 'required',
            'type' => 'required|string|in:flat,percent',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => '',
            'sales_agent_id' => 'nullable',
            'type_discount' => 'required|string',
        ];
    }

    
    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function selectSalesAgent(SalesAgent $salesAgent): void
    {
        $this->sales_agent_id = $salesAgent->id;
        $this->sales_agent_name = $salesAgent->name;
    }


    public function store(StoreCashBackAction $storeCashBackAction): void
    {
        $data = $this->validate();
        $data['merchant_id'] = $this->merchant_id;
        DB::beginTransaction();
        $storeCashBackAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCashBackAdminMerchants');
        $this->alert('success', 'Coupon Code Created Successfully');
        $this->reset('code','usages_left','min_order_value', 'discount_value','max_discount','expired_at', 'type','status','limit_per_user','sales_agent_name','sales_agent_id','type_discount');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.merchants.create');
    }
}
