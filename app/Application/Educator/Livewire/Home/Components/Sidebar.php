<?php
namespace Application\Educator\Livewire\Home\Components;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class Sidebar extends Component
{
    public Educator $educator;
    public int $reviewCount = 0;

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load(['profile', 'files']);
        $this->reviewCount = $this->educator->assignments()
            ->where('status', 'completed')
            ->count();
    }

    public function render()
    {
        return view('educator::livewire.home.components.sidebar');
    }
}
