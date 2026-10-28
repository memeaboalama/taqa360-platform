<?php

namespace Application\Admin\Livewire\SalesAgent\Modal;

use App\Domain\Admin\Actions\Dashboard\SalesAgent\UpdateSalesAgentAction;
use App\Support\Traits\Alerts\WithLocalizedAlerts;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ResetPassword extends Component
{
    use LivewireAlert;
    use WithLocalizedAlerts;

    public int|null $salesAgentId = null;
    public $password;
    public $password_confirmation;

    protected $listeners = ['resetSalesAgentPassword'];

    public function resetSalesAgentPassword($id): void
    {
        $this->salesAgentId = (int) $id;
        $this->reset('password', 'password_confirmation');
        $this->resetErrorBag();
        $this->emit('openModal', '#resetSalesAgentPassword');
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:50|confirmed',
        ];
    }

    public function resetPassword(UpdateSalesAgentAction $updateSalesAgentAction): void
    {
        $data = $this->validate();

        DB::beginTransaction();
        try {
            $updateSalesAgentAction->handle($this->salesAgentId, ['password' => $data['password']]);
            DB::commit();
            $this->emit('closeModal', '#resetSalesAgentPassword');
            $this->emit('refreshDatatable');
            $this->reset('password', 'password_confirmation', 'salesAgentId');
            $this->localizedAlert('success', __('admin::home.password_reset_successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->localizedAlert('error', __('admin::home.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.modal.reset-password');
    }
}