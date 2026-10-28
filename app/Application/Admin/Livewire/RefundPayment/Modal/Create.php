<?php

namespace Application\Admin\Livewire\RefundPayment\Modal;

use App\Domain\Admin\Actions\Dashboard\RefundPayment\StoreRefundPaymentAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use LivewireAlert;

    protected $listeners = ['refreshOrderData'];

    public $type = 'toWallet',
        $order_id = null,
        $order_code = null,
        $payment_id = null,
        $payment = null,
        $refund_payment_id = null,
        $payment_amount = null,
        $amount = 0,
        $currency = 'SAR',
        $status = '',
        $note = null,
        $user_id = null,
        $user_name = null,
        $refund_payment_amount_last = 0,
        $calculated_on = 'company',
        $merchant_name = '';
    public  $refundpayment;
    public MerchantOrder  $orderNew;
    public $messageFlag = false;

    protected StoreRefundPaymentAction $storeRefundPaymentAction;

    public function mount(MerchantOrder $order)
    {
        if ($order)
            $this->refreshOrderData($order);
    }
    protected function rules(): array
    {
        return [
            'type' => 'required|string|in:toWallet,toAccount',
            'order_id' => 'required',
            'payment_id' => 'required',
            'user_id' => 'required',
            'refund_payment_id' => '',
            'amount' => 'required',
            'currency' => 'required',
            'note' => 'required|string',
            'user_name' => 'required|string',
            'calculated_on' => 'required|string|in:company,store,delivery',
        ];
    }




    public function refreshOrderData(MerchantOrder $order)
    {
        $this->orderNew = $order;
        $this->order_id = $order->id;
        $this->order_code = $order->code;
        $this->user_id = $order->user_id;
        $this->user_name = $order->client_name;
        $this->payment = $order?->refPayment;
        $this->payment_id = $order?->refPayment?->payment_id;
        $this->payment_amount = $order?->refPayment?->amount / 100;
        $this->refundpayment = $order?->refundpayment;
        $this->merchant_name = $order?->merchant_name;
        $this->status = $order?->status?->trans();
        $this->refund_payment_amount_last = $order?->refundpayment?->sum('amount');
    }
    public function store(StoreRefundPaymentAction $storeRefundPaymentAction): void
    {
        $sumPay = ($this->refund_payment_amount_last + $this->amount);
        if ($sumPay <= $this->payment_amount) {
            $this->messageFlag = false;
            $this->type = 'toWallet';
            $data = $this->validate();
            try {
                DB::beginTransaction();
                $walletOrderAction = new WalletOrderAction();
                $walletOrderAction->add_wallet_refund_payment($this->orderNew, "Wallet Refund Payment", $this->amount);
                $storeRefundPaymentAction->handle($data);
                $this->emit('refreshDatatable');
                $this->emit('closeModal', '#createRefundPayment');
                $this->alert('success', 'RefundPayment Add Successfully');
                $this->reset(
                    'type',
                    'order_id',
                    'order_code',
                    'payment_id',
                    'user_id',
                    'refund_payment_id',
                    'amount',
                    'payment_amount',
                    'currency',
                    'note',
                    'user_name',
                    'refund_payment_amount_last',
                    'refundpayment',
                    'status',
                    'merchant_name',
                    'calculated_on',
                );
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                Log::error($ex);
            }
        } else {
            $this->messageFlag = true;
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.refundpayment.modal.create');
    }
}
