<?php

namespace Application\Educator\Livewire\Profile;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class About extends Component
{
    public ?string $aboutText;

    public function mount($educatorId)
    {
        $educator = Educator::findOrFail($educatorId);
        $this->aboutText = $educator->about ?? 'أستاذ جامعي متخصص في علوم الحاسوب مع خبرة تزيد عن 15 عاماً في التدريس والبحث الأكاديمي. حاصل على درجة الدكتوراه في الذكاء الاصطناعي من جامعة الملك سعود. أقدم خدمات متنوعة في إعداد البحوث الأكاديمية، وحل الواجبات البرمجية، والتدريس الخصوصي في مختلف لغات البرمجة.';
    }

    public function render()
    {
        return view('educator::livewire.profile.about');
    }
}
