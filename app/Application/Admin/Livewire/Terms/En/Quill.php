<?php

namespace Application\Admin\Livewire\Terms\En;
use Livewire\Component;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_EN = 'quill_value_updated_en';

    public $value_en;

    public $quillId_en;

    public function mount($value_en = ''){
        $this->value_en = $value_en;
        $this->quillId_en = 'editor-'.uniqid();
    }

    public function updatedValueEn($value) {
        $this->emit(self::EVENT_VALUE_UPDATED_EN, $this->value_en);
    }

    public function render()
    {
        return view('admin::livewire.terms.en.quill');
    }
}
