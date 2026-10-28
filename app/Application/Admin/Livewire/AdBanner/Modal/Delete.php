<?php

namespace Application\Admin\Livewire\AdBanner\Modal;

use App\Domain\AppBase\Actions\AdBanner\DeleteBannerAction;
use Livewire\Component;

class Delete extends Component
{
    public $bannerId;

    protected $listeners = ['openDeleteModal'];

    public function openDeleteModal(int $bannerId)
    {
        $this->bannerId = $bannerId;
        $this->dispatch('open-modal', 'deleteBannerModal');
    }

    public function delete(DeleteBannerAction $action)
    {
        $action->handle($this->bannerId);

        $this->dispatch('close-modal', 'deleteBannerModal');
        $this->dispatch('banner-deleted');
        $this->dispatch('success', __('admin::home.Deleted Successfully'));
    }

    public function render()
    {
        return view('admin::livewire.ad-banner.modal.delete');
    }
}
