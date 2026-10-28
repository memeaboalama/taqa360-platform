<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delivery extends Component
{
    use LivewireAlert;
    public $vat, $delivery_km, $delivery_fees, $extra_km_fees;
    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle(['vat', 'delivery_km', 'delivery_fees', 'extra_km_fees']));
    }

    protected function rules(): array
    {
        return [
            'vat' => 'required|numeric',
            'delivery_km' => 'required|numeric',
            'delivery_fees' => 'required|numeric',
            'extra_km_fees' => 'required|numeric',
        ];
    }

    public function nickNames()
    {
        return [
            'vat' => trans('admin::home.vat'),
            'delivery_km' => trans('admin::home.Delivery Default kilometers'),
            'delivery_fees' => trans('admin::home.Delivery Default Fees for Default kilometers'),
            'extra_km_fees' => trans('admin::home.Extra Delivery Fees for every kilometer'),
        ];
    }

    public function updateSearchCapacity(UpdateGeneralSettingAction $updateGeneralSettingAction): void
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
        $this->vat = $settings?->vat;
        $this->delivery_km = $settings?->delivery_km;
        $this->delivery_fees = $settings?->delivery_fees;
        $this->extra_km_fees = $settings?->extra_km_fees;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.delivery');
    }
}
