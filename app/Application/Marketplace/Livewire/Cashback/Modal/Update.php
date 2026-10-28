<?php

namespace Application\Marketplace\Livewire\Cashback\Modal;

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
    public CashBack $cashback;
    public $limit_per_user;
    public $is_custom = 1;
    public $page = "update";



    protected $listeners = ['updateMerchantCashback'];

    public function updateMerchantCashback($id, FetchSingleCashBackAction $fetchSingleCashbackAction): void
    {

        $this->cashback = $fetchSingleCashbackAction->handle($id);
        if ($this->cashback) {
            $this->name = $this->cashback->name;
            $this->usages_left = $this->cashback->usages_left;
            $this->limit_per_user = $this->cashback->limit_per_user;
            $this->status = $this->cashback->status->value;
            $this->type = $this->cashback->type;
            $this->discount_value = $this->cashback->discount_value;
            $this->max_discount = $this->cashback->max_discount;
            $this->min_order_value = $this->cashback->min_order_value;
            $this->is_custom = $this->cashback->is_custom;
            $this->expired_at = $this->cashback->expired_at->format('Y-m-d');
            $this->emit('openModal', '#updateMerchantCashback');
        }
    }


    protected function rules(): array
    {
        return [
            'name' => 'required|unique:cash_backs,name,'.$this->cashback->id,
            'usages_left' => 'required',
            'limit_per_user' => 'required',
            'min_order_value' => 'required',
            'max_discount' => 'required',
            'discount_value' => 'required',
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

    public function store(UpdateCashBackAction $updateCashbackAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateCashbackAction->handle($this->cashback->id ,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateMerchantCashback');
        $this->alert('success', __('admin::home.Cash Back Updated Successfully'));
        DB::commit();
    }

    public function customHandle(){
        $this->is_custom = !$this->is_custom;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.cashback.modal.update');
    }
}
