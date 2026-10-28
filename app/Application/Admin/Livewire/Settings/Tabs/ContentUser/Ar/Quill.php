<?php

namespace Application\Admin\Livewire\Settings\Tabs\ContentUser\Ar;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_USER_AR = 'quill_value_updated_user_ar';

    public $value_user_ar;

    public $quillUserId_ar;

    public function mount($value_user_ar = ''){
        $this->value_user_ar = $value_user_ar;
        $this->quillUserId_ar = 'settings-user-content-ar';
    }

    public function updatedValueUserAr($value_user_ar) {
        $this->emit(self::EVENT_VALUE_UPDATED_USER_AR, $this->value_user_ar);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.contentuser.ar.quill');
    }
}
