<?php

namespace Application\Marketplace\Livewire\Promocode\Modal;

use App\Domain\AppBase\Actions\Promocode\StorePromocodeAction;
use App\Domain\AppBase\Models\Promocode;
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
    public $merchant_id;

    protected $listeners = ['selectSalesAgent'];
    public function mount()
    {
        $this->merchant_id = resolve('_InitialMerchant');
    }
    protected function rules(): array
    {
        return [
            'code' => 'required|unique:promocodes,code',
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
            'merchant_id' => '',
            'type_discount' => 'required|string|in:delivery,order,total',
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


    public function store(StorePromocodeAction $storePromocodeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        Promocode::create($data);
        // $storePromocodeAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createMerchantPromocode');
        $this->alert('success', __('admin::home.Coupon Code Created Successfully'));
        $this->reset('code', 'usages_left', 'min_order_value', 'discount_value', 'max_discount', 'expired_at', 'type', 'status', 'limit_per_user', 'sales_agent_name', 'sales_agent_id', 'type_discount');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.promocode.modal.create');
    }
}
