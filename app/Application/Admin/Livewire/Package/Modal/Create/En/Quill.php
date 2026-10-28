<?php

namespace Application\Admin\Livewire\Package\Modal\Create\En;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_CREATE_VALUE_UPDATED_EN = 'quill_create_value_updated_en';

    public $create_value_en;

    public $create_quillId_en;

    public function mount($create_value_en = ''){
        $this->create_value_en = $create_value_en;
        $this->create_quillId_en = 'package-terms-create-en';
    }

    public function updatedCreateValueEn($value) {
        $this->emit(self::EVENT_CREATE_VALUE_UPDATED_EN, $this->create_value_en);
    }

    public function render()
    {
        return view('admin::livewire.package.modal.create.en.quill');
    }
}
