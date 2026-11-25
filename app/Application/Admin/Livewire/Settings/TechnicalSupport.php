<?php

namespace Application\Admin\Livewire\Settings;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TechnicalSupport extends Component
{
    use LivewireAlert;

    public $support_email;
    public $support_phone;
    public $canEdit = false;

    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction): void
    {
        abort_unless($this->canViewSettings(), 403);

        $settings = $fetchGeneralSettingsAction->handle(['support_email', 'support_phone']);
        $this->support_email = $settings?->support_email;
        $this->support_phone = $settings?->support_phone;
        $this->canEdit = $this->canEditSettings();
    }

    protected function rules(): array
    {
        return [
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
        ];
    }

    public function nickNames(): array
    {
        return [
            'support_email' => trans('admin::home.Support Email'),
            'support_phone' => trans('admin::home.Support Phone'),
        ];
    }

    public function save(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        abort_unless($this->canEditSettings(), 403);

        $data = $this->validate(attributes: $this->nickNames());
        $settings = DB::transaction(fn () => $updateGeneralSettingAction->handle($data));
        $this->support_email = $settings?->support_email;
        $this->support_phone = $settings?->support_phone;

        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    private function canViewSettings(): bool
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return false;
        }

        if ($admin->type === 'admin') {
            return true;
        }

        setPermissionsTeamId(1);

        return $admin->hasAnyPermission([
            'admin',
            'show profile_settings',
            'show login/ register page content_settings',
            'show search capacity_settings',
            'show delivery fees_settings',
            'edit profile_settings',
            'edit login/ register page content_settings',
            'edit search capacity_settings',
            'edit delivery fees_settings',
        ]);
    }

    private function canEditSettings(): bool
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return false;
        }

        if ($admin->type === 'admin') {
            return true;
        }

        setPermissionsTeamId(1);

        return $admin->hasAnyPermission([
            'admin',
            'edit profile_settings',
            'edit login/ register page content_settings',
            'edit search capacity_settings',
            'edit delivery fees_settings',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.technical-support');
    }
}
