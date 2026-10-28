<?php

namespace Application\Admin\Livewire\Region\Modal;

use App\Domain\Admin\Actions\Dashboard\Region\DestroyRegionAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteRegion'];

    public function deleteRegion($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteRegion');
    }

    public function destroy(DestroyRegionAction $destroyRegionAction): void
    {
        DB::beginTransaction();
        $destroyRegionAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteRegion');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Region Deleted Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.region.modal.delete');
    }

}