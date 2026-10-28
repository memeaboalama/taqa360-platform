<?php

namespace Application\Marketplace\Livewire\Menu\Component\Menu\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\DestroyServiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;
    public int|null $element_id = null;
    protected $listeners = ['deleteMenu'];

    public function deleteMenu($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteMenu');
    }

    public function destroy(DestroyServiceAction $destroyServiceAction): void
    {
        DB::beginTransaction();
        $menu = $destroyServiceAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteMenu');
        $this->emit('refreshMenus');
        $this->alert('success', __('marketplace::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.menu.modal.delete');
    }
}
