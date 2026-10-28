<?php

namespace Application\Admin\Livewire\AdPlacement\Modal;

use App\Domain\AppBase\Actions\AdPlacement\DeletePlacementAction;
use Livewire\Component;

class Delete extends Component
{
    public $placementId;

    protected $listeners = ['openDeleteModal'];

    public function openDeleteModal(int $placementId)
    {
        $this->placementId = $placementId;
        $this->dispatch('open-modal', 'deletePlacementModal');
    }

    public function delete(DeletePlacementAction $action)
    {
        $action->handle($this->placementId);

        $this->dispatch('close-modal', 'deletePlacementModal');
        $this->dispatch('placement-deleted');
        $this->dispatch('success', __('admin::home.Deleted Successfully'));
    }

    public function render()
    {
        return view('admin::livewire.ad-placement.modal.delete');
    }
}
