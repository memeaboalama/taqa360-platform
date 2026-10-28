<?php

namespace Application\Admin\Livewire\Merchant\Modal;

use App\Domain\Admin\Actions\Dashboard\Merchant\ResetMerchantPasswordAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ResetPassword extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public $password;
    public $password_confirmation;
    public bool $show_password = false;
    public bool $show_password_confirmation = false;

    protected $listeners = ['resetMerchantPassword'];

    public function resetMerchantPassword($id): void
    {
        $this->element_id = $id;
        $this->password = null;
        $this->password_confirmation = null;
        $this->show_password = false;
        $this->show_password_confirmation = false;
        $this->resetErrorBag();
        $this->emit('openModal', '#resetMerchantPassword');
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function store(ResetMerchantPasswordAction $resetMerchantPasswordAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $resetMerchantPasswordAction->handle($this->element_id, $data);
        $this->emit('closeModal', '#resetMerchantPassword');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.password_reset_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.modal.reset-password');
    }
}
