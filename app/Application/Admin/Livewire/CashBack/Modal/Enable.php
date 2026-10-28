<?php

namespace Application\Admin\Livewire\CashBack\Modal;

use App\Domain\AppBase\Actions\CashBack\EnableCashBackAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Carbon\Carbon;
class Enable extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public int|null $period = 0;
    protected $listeners = ['enableCashBack'];

    public function enableCashBack($id,$period): void
    {
        $this->element_id = $id;
        $this->period = $period;
        $this->emit('openModal', '#enableCashBack');
    }
    public function enable(EnableCashBackAction $enableCashBackAction): void
    {
        $currentDate = Carbon::now();
        $expired = $currentDate->addDays($this->period);
        DB::beginTransaction();
        $enableCashBackAction->handle($this->element_id, $expired);
        $this->emit('closeModal', '#enableCashBack');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Coupon Enable Successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.cashback.modal.enable');
    }
}
