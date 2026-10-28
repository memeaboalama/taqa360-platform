<?php

namespace Application\Admin\Livewire\CashBack\Modal\Merchants;

use App\Domain\AppBase\Actions\CashBack\FetchSingleCashBackAction;
use App\Domain\AppBase\Actions\CashBack\UpdateCashBackAction;
use App\Domain\AppBase\Models\CashBack;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $type = 'percent';
    public string $type_discount = "order";

    public string $code;
    public int $usages_left;
    public float $min_order_value;
    public float|null $max_discount;
    public float|null $discount_value;
    public $expired_at;
    public CashBack $CashBack;
    public $limit_per_user;
    public $is_custom = 1;
    public $sales_agent_id;
    public $sales_agent_name;
    public $page = "update";



    protected $listeners = ['updateCashBackAdminMerchants','selectSalesAgent'];

    public function updateCashBackAdminMerchants($id, FetchSingleCashBackAction $fetchSingleCashBackAction): void
    {

        $this->CashBack = $fetchSingleCashBackAction->handle($id);
        if ($this->CashBack) {
            $this->code = $this->CashBack->code;
            $this->usages_left = $this->CashBack->usages_left;
            $this->limit_per_user = $this->CashBack->limit_per_user;
            $this->status = $this->CashBack->status->value;
            $this->type = $this->CashBack->type;
            $this->type_discount = $this->CashBack?->type_discount;
            $this->discount_value = $this->CashBack->discount_value;
            $this->max_discount = $this->CashBack->max_discount;
            $this->min_order_value = $this->CashBack->min_order_value;
            $this->is_custom = $this->CashBack->is_custom;
            $this->sales_agent_id = $this->CashBack->sales_agent_id;
            $this->expired_at = $this->CashBack->expired_at->format('Y-m-d');
            $this->emit('openModal', '#updateCashBackAdminMerchants');
        }
    }


    protected function rules(): array
    {
        return [
            'code' => 'required|unique:CashBacks,code,'.$this->CashBack->id,
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
            'type_discount' => 'string',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateCashBackAction $updateCashBackAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateCashBackAction->handle($this->CashBack->id ,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateCashBackAdminMerchants');
        $this->alert('success', 'Coupon Code Updated Successfully');
        DB::commit();
    }

    public function customHandle(){
        $this->is_custom = !$this->is_custom;
    }

    public function selectSalesAgent(SalesAgent $salesAgent): void
    {
        $this->sales_agent_id = $salesAgent->id;
        $this->sales_agent_name = $salesAgent->name;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.merchants.update');
    }
}
