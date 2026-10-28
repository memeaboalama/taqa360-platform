<?php

namespace Application\Admin\Livewire\Catalog\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\DestroyCategoryAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteCatalogCategory'];

    public function deleteCatalogCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteCatalogCategory');
    }

    public function destroy(DestroyCategoryAction $destroyCategoryAction): void
    {
        DB::beginTransaction();
        $destroyCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteCatalogCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::catalog.messages.category_deleted'));
        DB::commit();

        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.category.modal.delete');
    }
}
