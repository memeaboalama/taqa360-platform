<?php

namespace Application\Admin\Livewire\Policies;

use Application\Admin\Livewire\Policies\En\Quill as QuillEN;
use Application\Admin\Livewire\Policies\Ar\Quill as QuillAr;
use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public $settings;
    public $policies_en;
    public $policies_ar;
    public $listeners = [
        QuillEN::EVENT_VALUE_UPDATED_EN,
        QuillAr::EVENT_VALUE_UPDATED_AR,
    ];

    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->settings = $fetchGeneralSettingsAction->handle(['policies_en', 'policies_ar']);
        $this->policies_en = $this->settings?->policies_en;
        $this->policies_ar = $this->settings?->policies_ar;
    }

    public function quill_value_updated_en($value){
        $this->policies_en = $value;
    }

    public function quill_value_updated_ar($value){
        $this->policies_ar = $value;
    }

    public function nickNames()
    {
        return [
            'policies_en' => trans('admin::home.Privacy Policy in English'),
            'policies_ar' => trans('admin::home.Privacy Policy in Arabic'),
        ];
    }

    protected function rules(): array
    {
        return [
            'policies_en' => 'required',
            'policies_ar' => 'required',
        ];
    }

    public function updatePolicies(UpdateGeneralSettingAction $updateGeneralSettingAction){
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateGeneralSettingAction->handle($data);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.policies.update', [
            'policies_en' => $this->policies_en,
            'policies_ar' => $this->policies_ar,
        ]);
    }
}
