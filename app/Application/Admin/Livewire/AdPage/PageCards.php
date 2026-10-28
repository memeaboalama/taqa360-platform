<?php

namespace Application\Admin\Livewire\AdPage;

use App\Domain\AppBase\Models\AdPage;
use Livewire\Component;
use Livewire\Attributes\On;

class PageCards extends Component
{
    #[On('page-created')]
    #[On('page-updated')]
    #[On('page-deleted')]
    public function render()
    {
        $pages = AdPage::withCount(['placements', 'activePlacements'])
            ->orderBy('sort_order')
            ->get();

        return view('admin::livewire.ad-page.page-cards', [
            'pages' => $pages
        ]);
    }
}
