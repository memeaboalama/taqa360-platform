<?php

namespace Application\Admin\Livewire\Wallet\Modal\Coupon;

use App\Domain\AppBase\Actions\WalletCoupon\StoreWalletCouponAction;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $type = 'flat';
    public string $type_amount = "order";
    public string $code = "";
    public $usages_left;
    public $min_order_value;
    public $max_amount;
    public $amount_value;
    public $expired_at;
    public $limit_per_user = '';

    public $is_custom = false;
    public $sales_agent_id;
    public $sales_agent_name;

    protected $listeners = ['selectSalesAgent'];
    protected function rules(): array
    {
        return [
            'code' => 'required',
            'usages_left' => 'required',
            'limit_per_user' => 'integer',
            // 'min_order_value' => 'required',
            // 'max_amount' => 'required',
            'amount_value' => 'required',
            // 'type' => 'required|string|in:flat',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',
            'sales_agent_id' => 'nullable',
            // 'type_amount' => 'required|string',
        ];
    }

    
    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function customHandle(){
        $this->is_custom = !$this->is_custom;
    }
    public function selectSalesAgent(SalesAgent $salesAgent): void
    {
        $this->sales_agent_id = $salesAgent->id;
        $this->sales_agent_name = $salesAgent->name;
    }


    public function store(StoreWalletCouponAction $storeWalletCouponAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeWalletCouponAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createWalletCoupon');
        $this->alert('success', __('admin::home.Coupon Code Created Successfully'));
        $this->reset('code','usages_left','min_order_value', 'amount_value','max_amount','expired_at', 'type','status','limit_per_user','sales_agent_name','sales_agent_id','type_amount');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.wallet.modal.coupon.create');
    }
}
