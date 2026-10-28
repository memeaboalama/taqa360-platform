<?php

namespace Application\Marketplace\Livewire\Service\Addons\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\Addon\DestroyAddonAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteAddon'];


    public function deleteAddon($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteAddon');
    }

    public function destroy(DestroyAddonAction $destroyAddonAction): void
    {
        DB::beginTransaction();
        $destroyAddonAction->handle($this->element_id);
        $this->emit( 'refreshAddons');
        $this->emit('closeModal', '#deleteAddon');
        $this->alert('success', 'Addon Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.service.addons.modal.delete');
    }
}
