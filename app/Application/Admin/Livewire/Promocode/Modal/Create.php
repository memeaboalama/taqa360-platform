<?php

namespace Application\Admin\Livewire\Promocode\Modal;

use App\Domain\AppBase\Actions\Promocode\StorePromocodeAction;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'inactive';
    public string $type = 'percent';
    public string $type_discount = "order";
    public string $code = "";
    public $usages_left;
    public $min_order_value;
    public $max_discount;
    public $discount_value;
    public $expired_at;
    public $limit_per_user;

    public $is_custom = false;
    public $sales_agent_id;
    public $sales_agent_name;
    public $period=1;

    protected $listeners = ['selectSalesAgent'];
    protected function rules(): array
    {
        return [
            'code' => 'required|unique:promocodes,code',
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
            'sales_agent_id' => 'nullable',
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
    public function selectSalesAgent(SalesAgent $salesAgent): void
    {
        $this->sales_agent_id = $salesAgent->id;
        $this->sales_agent_name = $salesAgent->name;
    }


    public function store(StorePromocodeAction $storePromocodeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storePromocodeAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createPromocode');
        $this->alert('success', __('admin::home.Coupon Code Created Successfully'));
        $this->reset('code','usages_left','min_order_value', 'discount_value','max_discount','period','expired_at', 'type','status','limit_per_user','sales_agent_name','sales_agent_id','type_discount');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.promocode.modal.create');
    }
}
