<?php

namespace Application\Admin\Livewire\Wallet\Modal\Coupon;

use App\Domain\AppBase\Actions\WalletCoupon\FetchSingleWalletCouponAction;
use App\Domain\AppBase\Actions\WalletCoupon\UpdateWalletCouponAction;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $type = 'falt';
    // public string $type_amount = "order";

    public string $code;
    public int $usages_left;
    // public float $min_order_value;
    // public float|null $max_amount;
    public float|null $amount_value;
    public $expired_at;
    public $walletcoupon;
    public $limit_per_user = 1;
    public $is_custom = false;
    public $sales_agent_id;
    public $sales_agent_name;



    protected $listeners = ['updateWalletCoupon','selectSalesAgent'];

    public function updateWalletCoupon($id, FetchSingleWalletCouponAction $fetchSingleWalletCouponAction): void
    {

        $this->walletcoupon = $fetchSingleWalletCouponAction->handle($id);
        if ($this->walletcoupon) {
            $this->code = $this->walletcoupon->code;
            $this->usages_left = $this->walletcoupon->usages_left;
            $this->limit_per_user = $this->walletcoupon->limit_per_user;
            $this->status = $this->walletcoupon->status->value;
            // $this->type = $this->walletcoupon->type;
            // $this->type_amount = $this->walletcoupon?->type_amount;
            $this->amount_value = $this->walletcoupon->amount_value;
            // $this->max_amount = $this->walletcoupon->max_amount;
            // $this->min_order_value = $this->walletcoupon->min_order_value;
            $this->is_custom = $this->walletcoupon->is_custom;
            $this->sales_agent_id = $this->walletcoupon->sales_agent_id;
            $this->sales_agent_name = $this->walletcoupon->sales_agent?->name;
            $this->expired_at = $this->walletcoupon->expired_at->format('Y-m-d');
            if ($this->walletcoupon->sales_agent) {
                $this->emit('setSalesAgent', $this->walletcoupon->sales_agent);
            } else {
                $this->emit('resetSearch');
            }
            $this->emit('openModal', '#updateWalletCoupon');
        }
    }

    protected function rules(): array
    {
        return [
            'code' => 'required',
            'usages_left' => 'required',
            'limit_per_user' => 'integer',
            // 'min_order_value' => 'required',
            // 'max_amount' => 'required',
            'amount_value' => 'required',
            // 'type' => 'required|string|in:flat,percent',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',
            'sales_agent_id' => 'nullable',
            // 'type_amount' => 'string',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateWalletCouponAction $updateWalletCouponAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateWalletCouponAction->handle($this->walletcoupon->id ,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateWalletCoupon');
        $this->alert('success', __('admin::home.Coupon Code Updated Successfully'));
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
        return view('admin::livewire.wallet.modal.coupon.update');
    }
}
