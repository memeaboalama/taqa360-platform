<?php

namespace Application\Educator\Livewire\Profile;

use Livewire\Component;

class Services extends Component
{
    public $services;

    public function mount($educatorId)
    {
        $this->services = [
            ['title' => 'البحوث الأكاديمية', 'description' => 'إعداد البحوث المتخصصة في علوم الحاسوب', 'price' => 'يبدأ من 300 ريال'],
            ['title' => 'التدريس الخصوصي', 'description' => 'دروس فردية في البرمجة وعلوم الحاسوب', 'price' => '150 ريال/ساعة'],
            ['title' => 'حل الواجبات البرمجية', 'description' => 'حلول متكاملة للواجبات والمشاريع', 'price' => 'يبدأ من 100 ريال'],
            ['title' => 'مراجعة الأكواد', 'description' => 'مراجعة وتحسين الأكواد البرمجية', 'price' => 'يبدأ من 80 ريال'],
        ];
    }

    public function render()
    {
        return view('educator::livewire.profile.services');
    }
}
