<?php

namespace Application\Admin\Livewire\Promocode\Modal\Merchants;

use App\Domain\AppBase\Actions\Promocode\FetchSinglePromocodeAction;
use App\Domain\AppBase\Actions\Promocode\UpdatePromocodeAction;
use App\Domain\AppBase\Models\Promocode;
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
    public Promocode $promocode;
    public $limit_per_user;
    public $is_custom = 1;
    public $sales_agent_id;
    public $sales_agent_name;
    public $page = "update";



    protected $listeners = ['updatePromocodeAdminMerchants','selectSalesAgent'];

    public function updatePromocodeAdminMerchants($id, FetchSinglePromocodeAction $fetchSinglePromocodeAction): void
    {

        $this->promocode = $fetchSinglePromocodeAction->handle($id);
        if ($this->promocode) {
            $this->code = $this->promocode->code;
            $this->usages_left = $this->promocode->usages_left;
            $this->limit_per_user = $this->promocode->limit_per_user;
            $this->status = $this->promocode->status->value;
            $this->type = $this->promocode->type;
            $this->type_discount = $this->promocode?->type_discount;
            $this->discount_value = $this->promocode->discount_value;
            $this->max_discount = $this->promocode->max_discount;
            $this->min_order_value = $this->promocode->min_order_value;
            $this->is_custom = $this->promocode->is_custom;
            $this->sales_agent_id = $this->promocode->sales_agent_id;
            $this->expired_at = $this->promocode->expired_at->format('Y-m-d');
            $this->emit('openModal', '#updatePromocodeAdminMerchants');
        }
    }


    protected function rules(): array
    {
        return [
            'code' => 'required|unique:promocodes,code,'.$this->promocode->id,
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

    public function store(UpdatePromocodeAction $updatePromocodeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updatePromocodeAction->handle($this->promocode->id ,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updatePromocodeAdminMerchants');
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
        return view('admin::livewire.promocode.modal.merchants.update');
    }
}
