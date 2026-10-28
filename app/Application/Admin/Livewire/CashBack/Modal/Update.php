<?php

namespace Application\Admin\Livewire\CashBack\Modal;

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

    public string $name;
    public int $usages_left;
    public float $min_order_value;
    public float|null $max_discount;
    public float|null $discount_value;
    public $expired_at;
    public CashBack $CashBack;
    public $limit_per_user;
    public $is_custom = false;
    public $page = "update";
    public $period=1;


    protected $listeners = ['updateCashBack','selectSalesAgent'];

    public function updateCashBack($id, FetchSingleCashBackAction $fetchSingleCashBackAction): void
    {

        $this->CashBack = $fetchSingleCashBackAction->handle($id);
        if ($this->CashBack) {
            $this->name = $this->CashBack->name;
            $this->usages_left = $this->CashBack->usages_left;
            $this->limit_per_user = $this->CashBack->limit_per_user;
            $this->status = $this->CashBack->status->value;
            $this->type = $this->CashBack->type;
            $this->discount_value = $this->CashBack->discount_value;
            $this->max_discount = $this->CashBack->max_discount;
            $this->min_order_value = $this->CashBack->min_order_value;
            $this->period = $this->CashBack->period;
            $this->expired_at = $this->CashBack->expired_at->format('Y-m-d');
            $this->emit('openModal', '#updateCashBack');
        }
    }


    protected function rules(): array
    {
        return [
            'name' => 'required|unique:cash_backs,name,'.$this->CashBack->id,
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
        $this->emit('closeModal', '#updateCashBack');
        $this->alert('success', __('admin::home.Coupon Code Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.update');
    }
}
