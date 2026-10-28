<?php
namespace Application\Admin\Livewire\Educator\Profile\Tabs;

use Domain\Educator\Models\Educator;
use Livewire\Component;

class Overview extends Component
{
    public Educator $educator;

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load(['bios', 'socialLinks', 'languages.language', 'profile.country', 'profile.city', 'bankInfo']);
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.tabs.overview');
    }
}
