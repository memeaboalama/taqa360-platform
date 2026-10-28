<?php

namespace Application\Admin\Livewire\Brand\Brand\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Brand\DestroyBrandAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteBrand'];

    public function deleteBrand($id): void
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
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.brand.modal.delete');
    }
}
