<?php

namespace Application\Admin\Livewire\BannerSpace\Modal;

use App\Domain\AppBase\Actions\AdPlacement\DeletePlacementAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeleteSpace extends Component
{
    use LivewireAlert;

    public $placementId;

    protected $listeners = ['openDeleteSpaceModal'];

    public function openDeleteSpaceModal($placementId)
    {
        $this->placementId = $placementId;
        $this->emit('openModal', '#deleteSpaceModal');
    }

    public function delete(DeletePlacementAction $action)
    {
        DB::beginTransaction();
        $action->handle($this->placementId);
        $this->emit('refreshSpaces');
        $this->emit('closeModal', '#deleteSpaceModal');
        $this->alert('success', __('admin::home.Deleted Successfully'));
        DB::commit();
    }

    public function render()
    {
        return view('admin::livewire.banner-space.modal.delete-space');
    }
}
