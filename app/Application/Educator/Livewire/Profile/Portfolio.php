<?php

namespace Application\Educator\Livewire\Profile;

use Livewire\Component;

class Portfolio extends Component
{
    public $portfolioItems;

    public function mount($educatorId)
    {
        $this->portfolioItems = [
            [
                'title' => 'نظام إدارة المكتبات الذكي',
                'meta' => '2023 · جامعة الملك سعود',
                'description' => 'تطوير نظام ذكي لإدارة المكتبات الجامعية باستخدام تقنيات الذكاء الاصطناعي',
                'color' => 'hsl(var(--primary-hsl))'
            ],
            [
                'title' => 'بحث في التعلم العميق',
                'meta' => '2022 · مجلة الحاسوب العربية',
                'description' => 'بحث محكم حول تطبيقات التعلم العميق في معالجة اللغة العربية',
                'color' => '#16a34a'
            ],
            [
                'title' => 'تطبيق الذكاء الاصطناعي للتشخيص الطبي',
                'meta' => '2021 · مستشفى الملك فهد',
                'description' => 'تطوير نظام ذكي لمساعدة الأطباء في التشخيص المبكر للأمراض',
                'color' => '#9333ea'
            ]
        ];
    }

    public function render()
    {
        return view('educator::livewire.profile.portfolio');
    }
}
