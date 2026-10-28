<?php

namespace Application\Admin\Livewire\Catalog\Brand\Modal;

use App\Domain\MarketPlace\Actions\Brand\DestroyBrandAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['openDeleteBrandModal'];

    public function openDeleteBrandModal($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteBrand');
    }

    public function destroy(DestroyBrandAction $destroyBrandAction): void
    {
        DB::beginTransaction();
        
        $destroyBrandAction->handle($this->element_id);
        
        $this->emit('closeModal', '#deleteBrand');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Deleted Successfully'));

        DB::commit();

        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.brand.modal.delete');
    }
}
