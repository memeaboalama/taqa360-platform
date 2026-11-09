<?php

namespace Application\Admin\Livewire\AdPlacement\Modal;

use App\Domain\AppBase\Actions\AdPlacement\DeletePlacementAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public $placementId;

    protected $listeners = ['openDeleteModal'];

    public function openDeleteModal(int $placementId)
    {
        $this->placementId = $placementId;
        $this->emit('openModal', '#deletePlacementModal');
    }

    public function delete(DeletePlacementAction $action)
    {
        $action->handle($this->placementId);

        $this->emit('closeModal', '#deletePlacementModal');
        $this->emit('placement-deleted');
        $this->alert('success', __('admin::home.Deleted Successfully'));
    }

    public function render()
    {
        return view('admin::livewire.ad-placement.modal.delete');
    }
}
