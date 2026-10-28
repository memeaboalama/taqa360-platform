<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use App\Domain\AppBase\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Orders extends Component
{
    use LivewireAlert;
    public $min_order_price, $pending_order_expiry, $confirmed_order_expiry, $resend_order_to_drivers;
    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle(['min_order_price','pending_order_expiry','confirmed_order_expiry','resend_order_to_drivers']));
    }

    protected function rules(): array
    {
        return [
            'min_order_price' => 'required|numeric',
            'pending_order_expiry' => 'required|numeric',
            'confirmed_order_expiry' => 'required|numeric',
            'resend_order_to_drivers' => 'required|numeric',
        ];
    }

    public function nickNames()
    {
        return [
            'min_order_price' => trans('admin::home.Minimum Order Price'),
            'pending_order_expiry' => trans('admin::home.Expiration Period Pending Order'),
            'confirmed_order_expiry' => trans('admin::home.Expiration Period Confirmed Order'),
            'resend_order_to_drivers' => trans('admin::home.Resend Order to drivers'),
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
        $this->min_order_price = $settings?->min_order_price;
        $this->pending_order_expiry = $settings?->pending_order_expiry;
        $this->confirmed_order_expiry = $settings?->confirmed_order_expiry;
        $this->resend_order_to_drivers = $settings?->resend_order_to_drivers;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.orders');
    }
}
