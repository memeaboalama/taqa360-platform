<?php

namespace Application\Educator\Livewire\Profile;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class Languages extends Component
{
    public $languages;

    public function mount($educatorId)
    {
        $this->languages = [
            ['name' => 'العربية', 'level' => 'لغة أم', 'style' => 'bg-green-100 text-green-800'],
            ['name' => 'الإنجليزية', 'level' => 'متقدم', 'style' => 'bg-blue-100 text-blue-800'],
            ['name' => 'الفرنسية', 'level' => 'متوسط', 'style' => 'bg-yellow-100 text-yellow-800'],
        ];
    }

    public function render()
    {
        return view('educator::livewire.profile.languages');
    }
}
