<?php

namespace Application\Company\Livewire\Employee\Modal;

use App\Domain\Company\Actions\Dashboard\Employee\FetchSingleCompanyEmployeeAction;
use App\Domain\Company\Actions\Dashboard\Employee\UpdateCompanyEmployeeAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchRolesAction;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public  $name, $email, $password, $phone, $password_confirmation, $status = 'active';
    public  $element_id, $companyId;
    protected $listeners = ['updateEmployee'];

    public $rolesShow, $role;

    public function mount(FetchRolesAction $fetchRolesAction)
    {
        $this->companyId = resolve('_InitialCompany');
        $this->rolesShow = $fetchRolesAction->fetchFilter(['id','name'], ['guard_name' => 'company_employee', 'team_id' => $this->companyId]);
    }

    public function updateEmployee($id, FetchSingleCompanyEmployeeAction $fetchSingleEmployeeAction): void
    {

        $employee = $fetchSingleEmployeeAction->handle($id, $this->companyId);

        if ($employee) {
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->phone = $employee->phone;
            $this->status = $employee->status;
            $this->element_id = $employee->id;
            $this->role = $employee->roles()?->first()?->id;
            $this->emit('openModal', '#updateEmployee');
        }
    }

    protected function rules(): array
    {
        return [
            'role' => 'required|exists:roles,id',
            'name' => 'required|string|min:8|max:120',
            'email' => 'required|string|min:8|max:200|unique:company_employees,email,' . $this->element_id,
            'phone'    =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'unique:company_employees,phone,' . $this->element_id],
            'password' => 'sometimes|string|min:6|max:50|confirmed|nullable',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateCompanyEmployeeAction $updateEmployeeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateEmployeeAction->handle($this->element_id, $data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateEmployee');
        $this->alert('success', __('company::home.data_updated_successfully'));
        $this->reset('name','role','email','phone','password','password_confirmation','status','element_id');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.employee.modal.update');
    }
}
