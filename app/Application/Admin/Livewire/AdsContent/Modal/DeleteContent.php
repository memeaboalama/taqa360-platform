<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdBanner\DeleteBannerAction;
use Illuminate\Support\Facades\DB;
use App\Domain\AppBase\Models\AdBanner;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeleteContent extends Component
{
    use LivewireAlert;

    public $bannerId;

    protected $listeners = ['openDeleteContentModal'];

    public function openDeleteContentModal($bannerId)
    {
        $this->bannerId = $bannerId;
        $this->emit('openModal', '#deleteContentModal');
    }

    public function delete(DeleteBannerAction $action)
    {
        $banner = AdBanner::with('placement')->find($this->bannerId);
        DB::transaction(fn () => $action->handle($this->bannerId));
        if ($banner && $banner->placement_id) {
            AdBanner::forgetStorefrontCacheForPlacement((int) $banner->placement_id);
        }

        $this->emit('banner-deleted');
        $this->emit('closeModal', '#deleteContentModal');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Deleted Successfully'));
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.delete-content');
    }
}
