<?php

namespace Application\Admin\Livewire\Settings\Tabs\Content\En;

use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_EN = 'quill_value_updated_en';

    public $value_en;

    public $quillId_en;

    public function mount($value_en = ''){
        $this->value_en = $value_en;
        $this->quillId_en = 'settings-auth-content-en';

    }

    public function updatedValueEn($value) {
        $this->emit(self::EVENT_VALUE_UPDATED_EN, $this->value_en);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.content.en.quill');
    }
}
