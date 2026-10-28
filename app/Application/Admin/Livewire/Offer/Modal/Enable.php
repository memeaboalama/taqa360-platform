<?php

namespace Application\Admin\Livewire\Offer\Modal;

use App\Domain\AppBase\Actions\Offer\EnableOfferAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Carbon\Carbon;
class Enable extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public int|null $period = 0;
    protected $listeners = ['enableOffer'];

    public function enableOffer($id,$period): void
    {
        $this->element_id = $id;
        $this->period = $period;
        $this->emit('openModal', '#enableOffer');
    }
    public function enable(EnableOfferAction $enableOfferAction): void
    {
        $currentDate = Carbon::now();
        $expired = $currentDate->addDays($this->period);
        DB::beginTransaction();
        $enableOfferAction->handle($this->element_id, $expired);
        $this->emit('closeModal', '#enableOffer');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Offer Enable Successfully');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.offer.modal.enable');
    }
}
