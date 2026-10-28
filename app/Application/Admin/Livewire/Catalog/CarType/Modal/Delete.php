<?php

namespace Application\Admin\Livewire\Catalog\CarType\Modal;

use App\Domain\AppBase\Actions\Car\DestroyCarTypeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['openDeleteCarTypeModal'];

    public function openDeleteCarTypeModal($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteCarType');
    }

    public function destroy(DestroyCarTypeAction $destroyCarTypeAction): void
    {
        DB::beginTransaction();
        try {
            $destroyCarTypeAction->handle($this->element_id);

            $this->emit('closeModal', '#deleteCarType');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::lang.Deleted Successfully'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.car-type.modal.delete');
    }
}
