<?php

namespace Application\Admin\Livewire\Country\Modal;

use App\Domain\Admin\Actions\Dashboard\Country\DestroyCountryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteCountry'];

    public function deleteCountry($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteCountry');
    }

    public function destroy(DestroyCountryAction $destroyCountryAction): void
    {
        DB::beginTransaction();
        $destroyCountryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteCountry');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Country Deleted Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.country.modal.delete');
    }

}