<?php

namespace Application\Admin\Livewire\Order\Modal;

use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use Domain\MarketPlace\Models\ElmOrder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Concerns\ToArray;

class History extends Component
{
    use LivewireAlert;

    public  $order_history ;
    protected $listeners = ['showOrderHistory'];

    public function showOrderHistory($id): void
    {
        $this->order_history = MerchantOrderHistory::select('action','created_at')->where('order_id', $id)->get();
        $this->order_history ? $this->emit('openModal', '#showOrderHistory'): null;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.modal.history');
    }
}
