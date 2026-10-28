<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use Domain\AppBase\Models\AdCategoryContent;
use Livewire\Component;

class DeleteCategoryContent extends Component
{
    public $contentId;
    
    protected $listeners = ['openDeleteCategoryContentModal'];

    public function openDeleteCategoryContentModal($contentId)
    {
        $this->contentId = $contentId;
        $this->emit('openModal', '#deleteCategoryContentModal');
    }

    public function destroy()
    {
        AdCategoryContent::find($this->contentId)?->delete();

        $this->emit('refreshContent');
        $this->emit('closeModal', '#deleteCategoryContentModal');
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.delete-category-content');
    }
}
