<?php

namespace Application\Admin\Livewire\Promocode\Modal;

use App\Domain\AppBase\Actions\Promocode\EnablePromocodeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Carbon\Carbon;
class Enable extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public int|null $period = 0;
    protected $listeners = ['enablePromocode'];

    public function enablePromocode($id,$period): void
    {
        $this->element_id = $id;
        $this->period = $period;
        $this->emit('openModal', '#enablePromocode');
    }
    public function enable(EnablePromocodeAction $enablePromocodeAction): void
    {
        $currentDate = Carbon::now();
        $expired = $currentDate->addDays($this->period);
        DB::beginTransaction();
        $enablePromocodeAction->handle($this->element_id, $expired);
        $this->emit('closeModal', '#enablePromocode');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Coupon Enable Successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.promocode.modal.enable');
    }
}
