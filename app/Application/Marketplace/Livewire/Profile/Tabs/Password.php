<?php

namespace Application\Marketplace\Livewire\Profile\Tabs;

use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdatePasswordAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Password extends Component
{
    use LivewireAlert;
    public string $old_password, $new_password, $new_password_confirmation;

    protected function rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|string|confirmed|min:6',
        ];
    }

    public function nickNames()
    {
        return [
            'old_password' => trans('marketplace::home.Old Password'),
            'new_password' => trans('marketplace::home.New Password'),
        ];
    }
    public function updatePassword(UpdatePasswordAction $updateBasicInfoAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        if(!Hash::check($data['old_password'], auth()->guard('merchant')->user()->password)){
            $errors = $this->getErrorBag();
            $errors->add('old_password', "Old Password Doesn't match");
            return;
        }
        DB::beginTransaction();
        $updateBasicInfoAction->handle($data);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();

    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.tabs.password');
    }
}
