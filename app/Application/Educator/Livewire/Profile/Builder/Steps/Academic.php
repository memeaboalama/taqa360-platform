<?php

namespace Application\Educator\Livewire\Profile\Builder\Steps;

use App\Support\Enum\Edu\EducationLevelEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Domain\Educator\Actions\Profile\UpdateAcademicMajorAction;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Academic extends Component
{
    use WithFileUploads, LivewireAlert;

    public Educator $educator;
    public $academicProfiles;
    public $approvedMajors;
    public array $educationLevels = [];

    public bool $showForm = false;
    public ?int $editingId = null;
    public array $formData = [];
    public $upload;
    public $existingFile = null;

    protected $listeners = ['executeDelete' => 'handleConfirmation'];

    protected function rules()
    {
        return [
            'formData.degree' => 'required|in:' . implode(',', EducationLevelEnum::values()),
            'formData.is_custom' => 'boolean',
            'formData.academic_major_id' => 'nullable|required_if:formData.is_custom,false|exists:academic_majors,id',
            'formData.custom_name' => 'nullable|required_if:formData.is_custom,true|string|max:255',
            'formData.graduation_institution' => 'required|string|max:255',
            'formData.graduation_year' => 'required|digits:4|integer|min:1950|max:' . date('Y'),
            'upload' => 'nullable|file|max:2048',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'formData.degree' => __('educator::builder.academic_info.education_level'),
            'formData.academic_major_id' => __('educator::builder.academic_info.approved_major'),
            'formData.custom_name' => __('educator::builder.academic_info.custom_major_name'),
            'formData.graduation_institution' => __('educator::builder.academic_info.granting_university'),
            'formData.graduation_year' => __('educator::builder.academic_info.graduation_year'),
            'upload' => __('educator::builder.academic_info.certificate_image'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadQualifications();
        foreach (EducationLevelEnum::cases() as $case) {
            $this->educationLevels[] = ['id' => $case->value, 'name' => $case->trans()];
        }
    }

    public function loadQualifications()
    {
        $this->academicProfiles = $this->educator->academicMajors()->with(['certificateFile', 'approvedMajor'])->get();
        $this->approvedMajors = AcademicMajor::where('status', 'active')->get();
    }

    public function resetForm()
    {
        $this->formData = ['is_custom' => false, 'degree' => '', 'academic_major_id' => ''];
        $this->upload = null;
        $this->existingFile = null;
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
        if ($this->editingId === $id) {
            $this->resetForm();
            return;
        }
        $qualification = $this->academicProfiles->find($id);
        if ($qualification) {
            $this->formData = $qualification->toArray();
            $this->existingFile = $qualification->certificateFile;
            if ($this->formData['is_custom']) {
                $this->formData['academic_major_id'] = 'custom';
            }
            $this->editingId = $id;
            $this->showForm = false;
            $this->resetErrorBag();
            $this->upload = null;
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'formData.academic_major_id') {
            $this->formData['is_custom'] = ($this->formData['academic_major_id'] === 'custom');
            if (!$this->formData['is_custom']) {
                $this->formData['custom_name'] = null;
            }
        }
    }

    public function saveQualification(UpdateAcademicMajorAction $updateAcademicMajorAction)
    {
        if (isset($this->formData['academic_major_id']) && $this->formData['academic_major_id'] === 'custom') {
            $this->formData['is_custom'] = true;
            $this->formData['academic_major_id'] = null;
        } else {
            $this->formData['is_custom'] = false;
        }
        $validatedData = $this->validate();
        try {
            $updateAcademicMajorAction->handle($this->educator, $validatedData['formData'], $this->editingId, $this->upload);
            $this->loadQualifications();
            $this->resetForm();
            $this->alert('success', __('educator::builder.messages.success_updated_qualification'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function confirmQualificationRemoval($id)
    {
        $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_text'), 'removeQualification', $id);
    }

    public function confirmImageRemoval()
    {
        if ($this->editingId) {
            $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_image_text'), 'removeExistingImage', $this->editingId);
        }
    }

    public function handleConfirmation(string $method, $params)
    {
        if ($method === 'removeQualification') {
            $this->removeQualification($params);
        }
        if ($method === 'removeExistingImage') {
            $this->removeExistingImage($params);
        }
    }

    public function removeQualification($id)
    {
        $imageService = resolve(ImageService::class);
        $qualification = $this->educator->academicMajors()->find($id);
        if ($qualification) {
            if ($qualification->certificateFile) {
                $imageService->destroyImg($qualification->certificateFile->path, $qualification->certificateFile->storage);
                $qualification->certificateFile->delete();
            }
            $qualification->delete();
            $this->loadQualifications();
            $this->resetForm();
            $this->alert('success', __('educator::builder.messages.qualification_deleted'));
        }
    }

    public function removeExistingImage($id)
    {
        $imageService = resolve(ImageService::class);
        $qualification = $this->educator->academicMajors()->find($id);
        if ($qualification && $qualification->certificateFile) {
            $imageService->destroyImg($qualification->certificateFile->path, $qualification->certificateFile->storage);
            $qualification->certificateFile->delete();
            $this->loadQualifications();
            $this->existingFile = null;
            $this->alert('success', __('educator::builder.messages.image_deleted_successfully'));
        }
    }

    public function saveAndNext()
    {
        $this->resetForm();
        $this->emitUp('stepCompleted', 2);
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.academic');
    }
}
