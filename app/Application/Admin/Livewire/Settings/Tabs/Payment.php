<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
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
            'visa_commission' => 'required|numeric|min:0|max:100',
            'master_commission' => 'required|numeric|min:0|max:100',
            'mada_commission' => 'required|numeric|min:0|max:100',
            'apple_commission' => 'nullable|numeric|min:0|max:100',
            'stc_commission' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function nickNames()
    {
        return [
            'visa_commission' => trans('admin::home.Visa'),
            'master_commission' => trans('admin::home.Master Card'),
            'mada_commission' => trans('admin::home.Mada'),
            'apple_commission' => trans('admin::home.Apple Pay'),
            'stc_commission' => trans('admin::home.STC Pay'),
        ];
    }

    public function updatePaymentSettings(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        $settings = DB::transaction(fn () => $updateGeneralSettingAction->handle($data));
        $this->fillData($settings);
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    protected function fillData($settings): void
    {
        $this->visa_commission = $settings?->visa_commission;
        $this->master_commission = $settings?->master_commission;
        $this->mada_commission = $settings?->mada_commission;
        $this->apple_commission = $settings?->apple_commission;
        $this->stc_commission = $settings?->stc_commission;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.payment');
    }
}
