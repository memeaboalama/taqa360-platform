<?php

namespace Application\Admin\Livewire\BannerSpace;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPageRepository;
use Livewire\Component;


class PageSelector extends Component
{
    public $selectedPageId;
    public $pages;

    public function mount()
    {
        $repository = new AdPageRepository(new AdPage());
        $this->pages = $repository->all();

        $homePage = $this->pages->firstWhere('key', 'home');
        $this->selectedPageId = $homePage?->id ?? $this->pages->first()?->id;
    }

    public function updatedSelectedPageId($value)
    {
        $this->emit('pageChanged', $value);
    }

    public function render()
    {
        return view('admin::livewire.banner-space.page-selector');
    }
}
