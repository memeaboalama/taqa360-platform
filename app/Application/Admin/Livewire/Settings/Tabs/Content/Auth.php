<?php

namespace Application\Admin\Livewire\Settings\Tabs\Content;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Application\Admin\Livewire\Settings\Tabs\Content\En\Quill as QuillEn;
use Application\Admin\Livewire\Settings\Tabs\Content\Ar\Quill as QuillAr;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Auth extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $auth_page_image = "", $auth_page_image_path, $diskName = 's3';
    public $auth_page_content_en, $auth_page_content_ar;

    public $listeners = [
        QuillEn::EVENT_VALUE_UPDATED_EN,
        QuillAr::EVENT_VALUE_UPDATED_AR,
    ];

    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle(['auth_page_image', 'auth_page_content_en', 'auth_page_content_ar']));
    }

    public function quill_value_updated_en($value_en){
        $this->auth_page_content_en = $value_en;
    }

    public function quill_value_updated_ar($value_ar){
        $this->auth_page_content_ar = $value_ar;
    }


    protected function rules(): array
    {
        return [
            'auth_page_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function nickNames()
    {
        return [
            'auth_page_image' => trans('admin::home.Login/Register Image'),
        ];
    }

    public function updateAuthPageContent(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        $data = $this->all();
        if($this->auth_page_image_path == null && $data['auth_page_image'] == null)
        {
            $this->addError('auth_page_image', __('admin::messages.IMAGE_REQUIRED'));
            return;
        }
        DB::beginTransaction();
        $settings = $updateGeneralSettingAction->handle($data);
        $this->fillData($settings);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($settings): void
    {
        $this->auth_page_image_path = $settings?->auth_page_image_path;
        $this->auth_page_content_en = $settings?->auth_page_content_en;
        $this->auth_page_content_ar = $settings?->auth_page_content_ar;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.content.auth',[
            'auth_page_content_en' => $this->auth_page_content_en,
            'auth_page_content_ar' => $this->auth_page_content_ar,
        ]);
    }
}
