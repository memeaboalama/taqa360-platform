<?php

namespace Application\Educator\Livewire\Profile;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class Sidebar extends Component
{
    public Educator $educator;

    public function mount($educatorId)
    {
        $this->educator = Educator::findOrFail($educatorId);
    }

    public function render()
    {
        return view('educator::livewire.profile.sidebar');
    }
}
