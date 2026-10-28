<?php

namespace Application\Admin\Livewire\Settings\Tabs\Content\Ar;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_AR = 'quill_value_updated_ar';

    public $value_ar;

    public $quillId_ar;

    public function mount($value_ar = ''){
        $this->value_ar = $value_ar;
        $this->quillId_ar = 'settings-auth-content-ar';
    }

    public function updatedValueAr($value) {
        $this->emit(self::EVENT_VALUE_UPDATED_AR, $this->value_ar);
        Log::debug($value);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.content.ar.quill');
    }
}
