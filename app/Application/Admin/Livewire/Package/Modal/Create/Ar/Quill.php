<?php

namespace Application\Admin\Livewire\Package\Modal\Create\Ar;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_CREATE_VALUE_UPDATED_AR = 'quill_create_value_updated_ar';

    public $create_value_ar;

    public $create_quillId_ar;

    public function mount($create_value_ar = ''){
        $this->create_value_ar = $create_value_ar;
        $this->create_quillId_ar = 'package-terms-create-ar';
    }

    public function updatedCreateValueAr($value) {
        $this->emit(self::EVENT_CREATE_VALUE_UPDATED_AR, $this->create_value_ar);
    }

    public function render()
    {
        return view('admin::livewire.package.modal.create.ar.quill');
    }
}
