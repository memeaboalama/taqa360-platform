<?php

namespace Application\Marketplace\Livewire\Employee\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Employee\UpdateEmployeePasswordAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UpdatePassword extends Component
{

    use LivewireAlert;

    public $password, $password_confirmation, $element_id = null;
    protected $listeners = ['updatePasswordEmployee'];

    public function updatePasswordEmployee($id): void
    {
        $this->element_id = $id;
        $this->reset('password', 'password_confirmation');
        $this->emit('openModal', '#updatePasswordEmployee');
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:50|confirmed',
        ];
    }

    public function store(UpdateEmployeePasswordAction $updateEmployeePasswordAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateEmployeePasswordAction->handle($this->element_id, $data['password']);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updatePasswordEmployee');
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        $this->reset('password', 'password_confirmation', 'element_id');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.employee.modal.update-password');
    }
}
