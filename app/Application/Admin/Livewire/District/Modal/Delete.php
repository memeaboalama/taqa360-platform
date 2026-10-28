<?php

namespace Application\Admin\Livewire\District\Modal;

use App\Domain\Admin\Actions\Dashboard\District\DestroyDistrictAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteDistrict'];

    public function deleteDistrict($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteDistrict');
    }

    public function destroy(DestroyDistrictAction $destroyCityAction): void
    {
        DB::beginTransaction();
        $destroyCityAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteDistrict');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.District Deleted Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.district.modal.delete');
    }

}
