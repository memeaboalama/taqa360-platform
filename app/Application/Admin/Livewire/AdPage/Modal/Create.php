<?php

namespace Application\Admin\Livewire\AdPage\Modal;

use App\Domain\AppBase\Models\AdPage;
use App\Support\Enum\StatusEnum;
use Livewire\Component;
use Livewire\Attributes\On;

class Create extends Component
{
    public $name = [];
    public $key;
    public $description = [];
    public $status = StatusEnum::ACTIVE;
    public $showOffcanvas = false;

    protected $rules = [
        'name.*' => 'required|string|max:255',
        'key' => 'required|string|unique:ad_pages,key',
        'status' => 'required|in:active,inactive',
    ];

    #[On('openCreateModal')]
    public function open()
    {
        $this->reset(['name', 'key', 'description', 'status']);
        $this->status = StatusEnum::ACTIVE->value;
        $this->showOffcanvas = true;
    }

    public function close()
    {
        $this->showOffcanvas = false;
    }

    public function save()
    {
        $this->validate();

        AdPage::create([
            'name' => $this->name,
            'key' => $this->key,
            'description' => $this->description,
            'status' => $this->status,
            'sort_order' => AdPage::max('sort_order') + 1,
        ]);

        $this->dispatch('page-created');
        $this->dispatch('show-toast', type: 'success', message: __('admin::lang.Page created successfully'));
        $this->close();
    }

    public function render()
    {
        return view('admin::livewire.ad-page.modal.create');
    }
}
