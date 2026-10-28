<?php

namespace Application\Admin\Livewire\Settings\Tabs\ContentUser\En;

use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Log;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_USER_EN = 'quill_value_updated_user_en';

    public $value_user_en;

    public $quillUserId_en;

    public function mount($value_user_en = ''){
        $this->value_user_en = $value_user_en;
        $this->quillUserId_en = 'settings-user-content-en';
    }

    public function updatedValueUserEn($value_user_en) {
        $this->emit(self::EVENT_VALUE_UPDATED_USER_EN, $this->value_user_en);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.contentuser.en.quill');
    }
}
