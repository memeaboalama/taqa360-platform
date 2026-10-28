<?php

namespace Application\Admin\Livewire\Order\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Order\CancelOrderAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Cancel extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['cancelOrder'];

    public function cancelOrder($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#cancelOrder');
    }

    public function destroy(CancelOrderAction $cancelOrderAction): void
    {
        DB::beginTransaction();
        $cancelOrderAction->handle($this->element_id);
        $this->emit('closeModal', '#cancelOrder');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Order Canceled Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.modal.cancel');
    }
}
