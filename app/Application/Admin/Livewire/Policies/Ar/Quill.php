<?php

namespace Application\Admin\Livewire\Policies\Ar;

use Livewire\Component;

class Quill extends Component
{
    const EVENT_VALUE_UPDATED_AR = 'quill_value_updated_ar';

    public $value_ar;

    public $quillId_ar;

    public function mount($value_ar = ''){
        $this->value_ar = $value_ar;
        $this->quillId_ar = 'editor-'.uniqid();
    }

    public function updatedValueAr($value) {
        $this->emit(self::EVENT_VALUE_UPDATED_AR, $this->value_ar);
    }

    public function render()
    {
        return view('admin::livewire.policies.ar.quill');
    }
}
