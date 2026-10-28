<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use Domain\AppBase\Models\Edu\Service;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Services extends Component
{
    public Educator $educator;
    public $educatorServices;
    public $approvedServices;

    use LivewireAlert;

    public bool $showForm = false;
    public array $formData = [];
    public ?int $editingId = null;

    protected $listeners = ['executeDelete'];

    protected function rules()
    {
        return [
            'formData.service_id' => 'nullable|required_without:formData.custom_name|exists:services,id',
            'formData.custom_name' => 'nullable|required_without:formData.service_id|string|max:255',
            'formData.custom_description' => 'required|string|max:1000',
        ];
    }

    protected function validationAttributes()
    {
        return trans('educator::builder.validation_attributes');
    }

    protected function messages()
    {
        return [
            'formData.service_id.required_if' => trans('educator::builder.validation_messages.service_id_required'),
            'formData.custom_name.required_if' => trans('educator::builder.validation_messages.custom_name_required'),
            'formData.custom_description.required' => trans('educator::builder.validation_messages.custom_description_required'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadServices();
        $this->resetForm();
    }

    public function loadServices()
    {
        $this->educatorServices = $this->educator->services()->with('approvedService')->get();
        $this->approvedServices = Service::where('status', 'active')->get();
    }

    public function resetForm()
    {
        $this->formData = ['service_id' => '', 'is_custom' => false, 'custom_name' => '', 'custom_description' => ''];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $service = $this->educatorServices->find($id);
        if ($service) {
            $this->formData = $service->toArray();
            if ($this->formData['is_custom']) {
                 $this->formData['service_id'] = 'custom';
            }
            $this->editingId = $id;
            $this->showForm = true;
            $this->resetErrorBag();
        }
    }

    public function updatedFormDataServiceId($value)
    {
        if ($value === 'custom') {
            $this->formData['is_custom'] = true;
        } else {
            $this->formData['is_custom'] = false;
        }
    }

    public function saveService()
    {
        $isCustom = (isset($this->formData['service_id']) && $this->formData['service_id'] === 'custom');
        $this->formData['is_custom'] = $isCustom;

        if ($isCustom) {
            $this->formData['service_id'] = null;
        }

        else {
            $this->formData['custom_name'] = null;
        }

        $this->validate();

        $this->educator->services()->updateOrCreate(['id' => $this->editingId], $this->formData);

        $this->loadServices();
        $this->resetForm();
        $this->alert('success', __('educator::builder.messages.service_saved'));
    }

    public function confirmServiceRemoval($id)
    {
        $this->emitUp('showDeleteConfirmation',
            trans('educator::builder.confirm_delete_title'),
            trans('educator::builder.confirm_delete_text'),
            'removeService',
            $id,
        );
    }

    public function executeDelete(string $method, $params)
    {
        if ($method === 'removeService') {
            $this->removeService($params);
        }
    }

    public function removeService($serviceId)
    {
        $this->educator->services()->find($serviceId)?->delete();
        $this->loadServices();
    }

    public function saveAndNext()
    {
        $this->emitUp('stepCompleted', 4);
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.services');
    }
}
