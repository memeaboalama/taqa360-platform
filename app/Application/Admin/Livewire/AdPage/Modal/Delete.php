<?php

namespace Application\Admin\Livewire\AdPage\Modal;

use App\Domain\AppBase\Models\AdPage;
use Livewire\Component;
use Livewire\Attributes\On;

class Delete extends Component
{
    public $pageId;
    public $name;
    public $showModal = false;

    #[On('openDeleteModal')]
    public function open($id)
    {
        $page = AdPage::findOrFail($id);
        $this->pageId = $page->id;
        $this->name = $page->name;
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset();
    }

    public function delete()
    {
        $page = AdPage::findOrFail($this->pageId);
        $page->delete();

        $this->dispatch('page-deleted');
        $this->dispatch('show-toast', type: 'success', message: __('admin::lang.Page deleted successfully'));
        $this->close();
    }

    public function render()
    {
        return view('admin::livewire.ad-page.modal.delete');
    }
}
