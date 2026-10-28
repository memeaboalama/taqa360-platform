<?php

namespace Application\Admin\Livewire\Offer\Modal;

use App\Domain\AppBase\Actions\Offer\DestroyOfferAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteOffer'];

    public function deleteOffer($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteOffer');
    }

    public function destroy(DestroyOfferAction $destroyOfferAction): void
    {
        DB::beginTransaction();
        $destroyOfferAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteOffer');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Offer Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.offer.modal.delete');
    }
}
