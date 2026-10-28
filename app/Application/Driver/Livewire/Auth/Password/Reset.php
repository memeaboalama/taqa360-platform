<?php

namespace Application\Driver\Livewire\Auth\Password;

use App\Domain\MarketPlace\Actions\Dashboard\Auth\Password\ResetPasswordAction;
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
        $this->phone = Session::get('phone');
    }

    public function nickNames()
    {
        return [
            'phone' => trans('driver::home.Phone Number'),
            'password' => trans('driver::home.Password'),
        ];
    }

    protected function rules(): array
    {
        return [
            'phone' =>  ['required','numeric','digits_between:9,10',new isValidPhone, 'exists:drivers,phone'],
            'password' => 'required|string|confirmed|min:4',
        ];
    }

    public function resetPassword(ResetPasswordAction $resetPasswordAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $driver = $resetPasswordAction->handle($data);
        DB::commit();
        if ($driver) return redirect(RouteServiceProvider::DRIVER_HOME);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.password.reset');
    }
}
