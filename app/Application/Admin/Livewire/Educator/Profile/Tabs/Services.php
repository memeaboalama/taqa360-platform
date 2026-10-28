<?php

namespace Application\Admin\Livewire\Educator\Profile\Tabs;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class Services extends Component
{
    public Educator $educator;
    public $services;
    public $skills;

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->services = $this->educator->services()->with('approvedService')->get();
        $this->skills = $this->educator->skills()->with('approvedSkill')->get();
    }

    public function getLevelLabel(int $level): string
    {
        return match ($level) {
            1 => trans('مبتدئ'),
            2 => trans('أساسي'),
            3 => trans('متوسط'),
            4 => trans('متقدم'),
            5 => trans('خبير'),
            default => '',
        };
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.tabs.services');
    }
}
