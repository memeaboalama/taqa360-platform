<?php

namespace Application\Company\Livewire\Employee\Modal;

use App\Domain\Company\Actions\Dashboard\Employee\StoreCompanyEmployeeAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchRolesAction;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{

    use LivewireAlert;

    public $name, $email, $password, $phone, $password_confirmation, $status = 'active';
    public $rolesShow, $role, $companyId;

    public function mount(FetchRolesAction $fetchRolesAction)
    {
        $this->companyId = resolve('_InitialCompany');
        $this->rolesShow = $fetchRolesAction->fetchFilter(['id','name'], ['guard_name' => 'company_employee', 'team_id' => $this->companyId]);
    }

    protected function rules(): array
    {
        return [
            'role' => 'required|exists:roles,id',
            'name' => 'required|string|min:6|max:120',
            'email' => 'required|string|min:8|max:200|unique:company_employees,email',
            'phone'    =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'unique:company_employees,phone'],
            'password' => 'required|string|min:6|max:50|confirmed',
            'status' => 'required|string|in:active,inactive',
        ];
    }


    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreCompanyEmployeeAction $storeEmployeeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $data['company_id'] = $this->companyId;
        $storeEmployeeAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createEmployee');
        $this->alert('success', __('company::home.data_saved_successfully'));
        DB::commit();
        $this->reset('name','role','email','phone','password','status','password_confirmation');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.employee.modal.create');
    }
}

