<?php

namespace Application\Company\Livewire\Auth\Password;

use App\Domain\Company\Actions\Dashboard\Auth\Password\ResetPasswordAction;
use App\Providers\RouteServiceProvider;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Reset extends Component
{
    use LivewireAlert;

    public $password, $password_confirmation, $phone;

    public function mount()
    {
        $this->phone = Session::get('company_phone');
    }

    public function nickNames()
    {
        return [
            'phone' => trans('company::home.Phone Number'),
            'password' => trans('company::home.Password'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:companies,phone'],
            'password' => 'required|string|confirmed|min:4',
        ];
    }

    public function resetPassword(ResetPasswordAction $resetPasswordAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $company = $resetPasswordAction->handle($data);
        DB::commit();
        if ($company) return redirect(RouteServiceProvider::COMPANY_HOME);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.password.reset');
    }
}
