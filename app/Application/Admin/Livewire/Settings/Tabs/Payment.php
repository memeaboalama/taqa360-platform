<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use App\Domain\AppBase\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Payment extends Component
{
    use LivewireAlert;
    public $visa_commission, $master_commission, $mada_commission, $apple_commission, $stc_commission;
    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle(['visa_commission','master_commission','mada_commission','apple_commission','stc_commission']));
    }

    protected function rules(): array
    {
        return [
            'visa_commission' => 'required|numeric',
            'master_commission' => 'required|numeric',
            'mada_commission' => 'required|numeric',
            // 'apple_commission' => 'required|numeric',
            // 'stc_commission' => 'required|numeric',
        ];
    }

    public function nickNames()
    {
        return [
            'visa_commission' => trans('admin::home.Visa'),
            'master_commission' => trans('admin::home.Master Card'),
            'mada_commission' => trans('admin::home.Mada'),
            // 'apple_commission' => trans('admin::home.Resend Order to drivers'),
            // 'stc_commission' => trans('admin::home.Resend Order to drivers'),
        ];
    }

    public function updateOrdersSeetings(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $settings = $updateGeneralSettingAction->handle($data);
        $this->fillData($settings);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($settings): void
    {
        $this->visa_commission = $settings?->visa_commission;
        $this->master_commission = $settings?->master_commission;
        $this->mada_commission = $settings?->mada_commission;
        // $this->apple_commission = $settings->apple_commission;
        // $this->stc_commission = $settings->stc_commission;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.payment');
    }
}
