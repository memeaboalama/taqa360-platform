<?php

namespace Application\Admin\Livewire\Banner\Modal;

use App\Domain\Admin\Actions\Dashboard\Banner\DestroyBannerAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteBanner'];

    public function deleteBanner($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteBanner');
    }

    public function destroy(DestroyBannerAction $destroyBannerAction): void
    {
        DB::beginTransaction();
        $destroyBannerAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteBanner');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Banner Deactivated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.banner.modal.delete');
    }
}
