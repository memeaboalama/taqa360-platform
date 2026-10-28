<?php

namespace Application\Admin\Livewire\Package\Modal\Update\En;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_UPDATE_VALUE_UPDATED_EN = 'quill_update_value_updated_en';

    public $update_value_en;

    public $update_quillId_en;


    public function mount($update_value_en = ''){
        $this->update_value_en = $update_value_en;
        $this->update_quillId_en = 'package-terms-update-en';
    }


    public function updatedUpdateValueEn($value) {
        $this->emit(self::EVENT_UPDATE_VALUE_UPDATED_EN, $this->update_value_en);
    }

    public function render()
    {
        return view('admin::livewire.package.modal.update.en.quill');
    }
}
