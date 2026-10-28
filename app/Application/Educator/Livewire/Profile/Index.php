<?php

namespace Application\Educator\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public int $educatorId;

    public function mount()
    {
        $this->educatorId = Auth::guard('educator')->id();
    }

    public function render()
    {
        return view('educator::livewire.profile.index', [
            'educator' => Auth::guard('educator')->user(),
        ]);
    }
}
