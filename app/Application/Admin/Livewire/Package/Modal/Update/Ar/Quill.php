<?php

namespace Application\Admin\Livewire\Package\Modal\Update\Ar;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_UPDATE_VALUE_UPDATED_AR = 'quill_update_value_updated_ar';

    public $update_value_ar;

    public $update_quillId_ar;

    public function mount($update_value_ar = ''){
        $this->update_value_ar = $update_value_ar;
        $this->update_quillId_ar = 'package-terms-update-ar';
    }

    public function updatedUpdateValueAr($value) {
        $this->emit(self::EVENT_UPDATE_VALUE_UPDATED_AR, $this->update_value_ar);
    }

    public function render()
    {
        return view('admin::livewire.package.modal.update.ar.quill');
    }
}
