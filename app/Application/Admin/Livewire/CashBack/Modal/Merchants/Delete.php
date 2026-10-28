<?php

namespace Application\Admin\Livewire\CashBack\Modal\Merchants;

use App\Domain\AppBase\Actions\CashBack\DestroyCashBackAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteCashBack'];

    public function deleteCashBack($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteCashBack');
    }

    public function destroy(DestroyCashBackAction $destroyCashBackAction): void
    {
        DB::beginTransaction();
        $destroyCashBackAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteCashBack');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Coupon code Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.delete');
    }
}
