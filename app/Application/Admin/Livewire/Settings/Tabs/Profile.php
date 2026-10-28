<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\Admin\Actions\Dashboard\Auth\UpdateProfileAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Profile extends Component
{
    use LivewireAlert;
    public $admin, $email, $name;

    public function mount()
    {
        $this->admin =  auth()->guard('admin')->user();
        $this->fillData($this->admin);

    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:admins,email,'. $this->admin->id,
            'name' => 'required|string',
        ];
    }

    public function nickNames()
    {
        return [
            'email' => trans('admin::home.email'),
            'name' => trans('admin::home.name'),
        ];
    }

    public function updateProfile(UpdateProfileAction $updateProfileAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $admin = $updateProfileAction->handle($this->admin->id ,$data);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($admin)
    {
        $this->email = $admin?->email;
        $this->name = $admin?->name;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.profile');
    }
}
