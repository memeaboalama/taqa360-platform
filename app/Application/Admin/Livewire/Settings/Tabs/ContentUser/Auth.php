<?php

namespace Application\Admin\Livewire\Settings\Tabs\ContentUser;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use Application\Admin\Livewire\Settings\Tabs\ContentUser\En\Quill as QuillUserEn;
use Application\Admin\Livewire\Settings\Tabs\ContentUser\Ar\Quill as QuillUserAr;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Auth extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $user_page_content_en, $user_page_content_ar;

    public $listeners = [
        QuillUserEn::EVENT_VALUE_UPDATED_USER_EN,
        QuillUserAr::EVENT_VALUE_UPDATED_USER_AR,
    ];

    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle([ 'user_page_content_en', 'user_page_content_ar']));
    }

    public function quill_value_updated_user_en($value_user_en){
        $this->user_page_content_en = $value_user_en;
    }

    public function quill_value_updated_user_ar($value_user_ar){
        $this->user_page_content_ar = '<div style="direction: rtl;"> '.$value_user_ar.' </div>';
    }


    protected function rules(): array
    {
        return [
            '' => '',
        ];
    }

   
    public function updateUserPageContent(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        $data = $this->all();
        Log::debug($data);
        DB::beginTransaction();
        $settings = $updateGeneralSettingAction->handle($data);
        $this->fillData($settings);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($settings): void
    {
        $this->user_page_content_en = $settings?->user_page_content_en;
        $this->user_page_content_ar = $settings?->user_page_content_ar;
        Log::debug($this->user_page_content_en);
        Log::debug($this->user_page_content_ar);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.contentuser.auth',[
            'user_page_content_en' => $this->user_page_content_en,
            'user_page_content_ar' => $this->user_page_content_ar,
        ]);
    }
}
