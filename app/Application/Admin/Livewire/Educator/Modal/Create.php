<?php

namespace Application\Admin\Livewire\Educator\Modal;

use App\Domain\Admin\Actions\Dashboard\Educator\StoreEducatorAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $name, $email, $phone, $password, $password_confirmation, $status = 'active';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:educators,email',
            'phone' => 'required|string|min:2|max:200|unique:educators,phone',
            'password' => 'required|string|min:6|max:50|confirmed',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreEducatorAction $storeEducatorAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeEducatorAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createEducator');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('name', 'email', 'phone', 'password', 'password_confirmation', 'status');
        $this->status = 'active';
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.educator.modal.create');
    }
}
