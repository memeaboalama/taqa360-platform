<?php

namespace Application\Educator\Livewire\Profile;

use Livewire\Component;

class Reviews extends Component
{
    public $reviews;

    public function mount($educatorId)
    {
        $this->reviews = [
            [
                'user_name' => 'سارة أحمد',
                'user_avatar' => 'https://via.placeholder.com/32',
                'rating' => 5,
                'date' => 'منذ أسبوع',
                'comment' => 'خدمة ممتازة وسرعة في التنفيذ. ساعدني الدكتور أحمد في إنجاز مشروع التخرج بأعلى جودة.'
            ],
            [
                'user_name' => 'محمد الشهري',
                'user_avatar' => 'https://via.placeholder.com/32',
                'rating' => 5,
                'date' => 'منذ أسبوعين',
                'comment' => 'أستاذ متميز ويشرح بطريقة واضحة ومفهومة. أنصح بالتعامل معه.'
            ],
            [
                'user_name' => 'فاطمة العلي',
                'user_avatar' => 'https://via.placeholder.com/32',
                'rating' => 4,
                'date' => 'منذ شهر',
                'comment' => 'عمل جيد والتزام بالمواعيد المحددة. شكراً لك دكتور.'
            ]
        ];
    }

    public function render()
    {
        return view('educator::livewire.profile.reviews');
    }
}
