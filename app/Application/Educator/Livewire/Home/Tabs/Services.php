<?php
namespace Application\Educator\Livewire\Home\Tabs;

use Domain\AppBase\Models\Edu\Service;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Services extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public $services;
    public $approvedServices;

    public bool $showFormModal = false;
    public ?int $editingId = null;
    public array $formData = [];

    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->approvedServices = Service::where('status', 'active')->get();
        $this->resetForm();
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = $this->educator->services()->with('approvedService')->get();
    }

    public function resetForm()
    {
        $this->formData = ['service_id' => '', 'is_custom' => false, 'custom_name' => '', 'custom_description' => ''];
        $this->editingId = null;
        $this->showFormModal = false;
        $this->resetErrorBag();
    }

    public function showAddForm()
    {
        $this->resetForm();
        $this->showFormModal = true;
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $service = $this->educator->services()->find($id);
        if (! $service) {
            return;
        }

        $this->formData = $service->toArray();
        if ($service->is_custom) {
            $this->formData['service_id'] = 'custom';
        }
        $this->editingId = (int) $id;
        $this->showFormModal = true;
        $this->resetErrorBag();
    }

    public function closeForm()
    {
        $this->resetForm();
    }

    public function updatedFormDataServiceId($value)
    {
        $this->formData['is_custom'] = $value === 'custom';
    }

    public function saveService()
    {
        $isCustom = ($this->formData['service_id'] ?? null) === 'custom';
        $this->formData['is_custom'] = $isCustom;

        if ($isCustom) {
            $this->formData['service_id'] = null;
        } else {
            $this->formData['custom_name'] = null;
        }

        $this->validate([
            'formData.service_id' => 'nullable|required_without:formData.custom_name|exists:services,id',
            'formData.custom_name' => 'nullable|required_without:formData.service_id|string|max:255',
            'formData.custom_description' => 'required|string|max:1000',
        ]);

        $this->educator->services()->updateOrCreate(['id' => $this->editingId], $this->formData);

        $this->resetForm();
        $this->loadServices();
        $this->alert('success', __('educator::builder.messages.service_saved'));
    }

    public function confirmDelete($id)
    {
        $this->deleteId = (int) $id;
        $this->showDeleteModal = true;
    }

    public function closeDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function executeDelete()
    {
        $service = $this->educator->services()->find($this->deleteId);
        if ($service) {
            $service->delete();
        }

        $this->closeDelete();
        $this->loadServices();
        $this->alert('success', __('educator::builder.messages.service_deleted'));
    }

    public function toggleStatus($id)
    {
        $service = $this->educator->services()->find($id);
        if ($service) {
            $service->update(['is_active' => ! $service->is_active]);
            $this->loadServices();
            $this->alert('success', __('educator::builder.messages.service_status_updated'));
        }
    }

    public function render()
    {
        return view('educator::livewire.home.tabs.services');
    }
}