<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdBanner\DeleteBannerAction;
use App\Domain\AppBase\Actions\AdBanner\ReorderActiveBannersAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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
        $placementKey = $banner?->placement?->key;

        DB::beginTransaction();
        $action->handle($this->bannerId);

        if ($placementKey) {
            Cache::forget("ecommerce_banners_{$placementKey}_en");
            Cache::forget("ecommerce_banners_{$placementKey}_ar");
        }

        if ($banner && $banner->placement_id) {
            (new ReorderActiveBannersAction())->handle($banner->placement_id);
        }

        $this->emit('banner-deleted');
        $this->emit('closeModal', '#deleteContentModal');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Deleted Successfully'));
        DB::commit();
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.delete-content');
    }
}
