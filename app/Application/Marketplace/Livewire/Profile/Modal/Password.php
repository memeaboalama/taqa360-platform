<?php

namespace Application\Marketplace\Livewire\Profile\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdatePasswordAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Password extends Component
{
    use LivewireAlert;

    public string $old_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    protected function rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|string|confirmed|min:6',
        ];
    }

    public function nickNames(): array
    {
        return [
            'old_password' => trans('marketplace::home.Old Password'),
            'new_password' => trans('marketplace::home.New Password'),
        ];
    }

    public function updatePassword(UpdatePasswordAction $updatePasswordAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        if (!Hash::check($data['old_password'], $this->getCurrentPassword())) {
            $this->addError('old_password', __('marketplace::home.old_password_incorrect'));
            return;
        }

        DB::beginTransaction();
        try {
            $updatePasswordAction->handle($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('new_password', __('marketplace::home.password_update_failed'));
            return;
        }

        $this->reset('old_password', 'new_password', 'new_password_confirmation');
        $this->emit('closeModal', '#updatePasswordModal');
        $this->alert('success', __('marketplace::home.password_updated_successfully'));
    }

    protected function getCurrentPassword(): string
    {
        if (auth()->guard('merchant')->check()) {
            return auth()->guard('merchant')->user()->password;
        }
        if (auth()->guard('merchant_employee')->check()) {
            return auth()->guard('merchant_employee')->user()->password;
        }
        return '';
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.modal.password');
    }
}