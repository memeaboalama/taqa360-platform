<?php

namespace Application\Admin\Livewire\AdPage\Modal;

use App\Domain\AppBase\Models\AdPage;
use App\Support\Enum\StatusEnum;
use Livewire\Component;
use Livewire\Attributes\On;

class Update extends Component
{
    public $pageId;
    public $name = [];
    public $key;
    public $description = [];
    public $status;
    public $showOffcanvas = false;

    protected $rules = [
        'name.*' => 'required|string|max:255',
        'key' => 'required|string',
        'status' => 'required|in:active,inactive',
    ];

    #[On('openUpdateModal')]
    public function open($id)
    {
        $page = AdPage::findOrFail($id);
        $this->pageId = $page->id;
        $this->name = $page->getTranslations('name');
        $this->key = $page->key;
        $this->description = $page->getTranslations('description');
        $this->status = $page->status->value;
        $this->showOffcanvas = true;
    }

    public function close()
    {
        $this->showOffcanvas = false;
        $this->reset();
    }

    public function save()
    {
        $this->validate();

        if (AdPage::where('key', $this->key)->where('id', '!=', $this->pageId)->exists()) {
            $this->addError('key', __('validation.unique', ['attribute' => 'key']));
            return;
        }

        $page = AdPage::findOrFail($this->pageId);
        $page->update([
            'name' => $this->name,
            'key' => $this->key,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->dispatch('page-updated');
        $this->dispatch('show-toast', type: 'success', message: __('admin::lang.Page updated successfully'));
        $this->close();
    }

    public function render()
    {
        return view('admin::livewire.ad-page.modal.update');
    }
}
