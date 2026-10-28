<?php

namespace Application\Admin\Livewire\RefundPayment\Table;

use App\Domain\Admin\Actions\Dashboard\Company\FetchCompanyAction;
use App\Domain\Admin\Actions\Dashboard\Company\FetchSingleCompanyAction;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use App\Domain\AppBase\Models\RefundPayment;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $type = null;
    public $order_id = null;
    public $payment_id= null;
    public $refund_payment_id = null;
    public $user_id = null;
    public $user_name = null;
    public $created_at = null;
    public array $filters = [];

    protected $listeners = [
        'refreshRefundPaymentFilter',
        'removeRefundPaymentFilter',
        'removeTypeFilter',
        'filterType',
    ];

    public function refreshRefundPaymentFilter(RefundPayment $refundpayment): void
    {
        $this->refund_payment_id = $refundpayment->refund_payment_id;
        $this->order_id = $refundpayment->order_id ?? null;
        $this->payment_id = $refundpayment->payment_id ?? null;
        $this->type = $refundpayment->type ?? null;
        $this->payment_id = $refundpayment->payment_id ?? null;
        $this->user_name = $refundpayment->user_name ?? null;
        $this->filters['refund_payment_id'] =  $this->refund_payment_id;
        $this->emit('refreshRefundPayments', $this->filters);
    }

    public function removeRefundPaymentFilter(): void
    {
        $this->refund_payment_id = null;
        $this->order_id = null;
        $this->payment_id = null;
        $this->type = null;
        $this->user_name = null;
        $this->filters['refund_payment_id'] = null;
        $this->emit('refreshRefundPayments', $this->filters);
    }

    public function filterType(): void
    {
        if ($this->type == "")
        {
            $this->type = null;
        }


        $this->filters['type'] =  $this->type;
        $this->emit('refreshRefundPayments', $this->filters);
    }

    public function removeTypeFilter(): void
    {
        $this->type = null;
        $this->filters['type'] = null;
        $this->emit('refreshRefundPayments', $this->filters);
    }

    public function render()
    {
        return view('admin::livewire.refundpayment.table.filter');
    }
}
