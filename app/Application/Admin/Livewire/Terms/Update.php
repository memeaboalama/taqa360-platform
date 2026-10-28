<?php

namespace Application\Admin\Livewire\Terms;

use Application\Admin\Livewire\Terms\En\Quill as QuillEn;
use Application\Admin\Livewire\Terms\Ar\Quill as QuillAr;
use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public $settings;
    public $terms_conditions_en;
    public $terms_conditions_ar;
    public $listeners = [
        QuillEn::EVENT_VALUE_UPDATED_EN,
        QuillAr::EVENT_VALUE_UPDATED_AR,
    ];

    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->settings = $fetchGeneralSettingsAction->handle(['terms_conditions_en', 'terms_conditions_ar']);
        $this->fillData($this->settings);
    }

    public function quill_value_updated_en($value){
        $this->terms_conditions_en = $value;
    }

    public function quill_value_updated_ar($value){
        $this->terms_conditions_ar = $value;
    }

    public function nickNames()
    {
        return [
            'terms_conditions_en' => trans('admin::home.Terms & Conditions in English'),
            'terms_conditions_ar' => trans('admin::home.Terms & Conditions in Arabic'),
        ];
    }

    protected function rules(): array
    {
        return [
            'terms_conditions_en' => 'required',
            'terms_conditions_ar' => 'required',
        ];
    }

    public function updateTerms(UpdateGeneralSettingAction $updateGeneralSettingAction){
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $this->fillData($updateGeneralSettingAction->handle($data));
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function fillData($settings)
    {
        $this->terms_conditions_en = $settings?->terms_conditions_en;
        $this->terms_conditions_ar = $settings?->terms_conditions_ar;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.terms.update',[
            'terms_conditions_en' => $this->terms_conditions_en,
            'terms_conditions_ar' => $this->terms_conditions_ar,
        ]);
    }
}
