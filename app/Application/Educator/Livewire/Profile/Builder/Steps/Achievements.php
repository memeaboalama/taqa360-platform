<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use App\Support\Services\ImagesServices\ImageService;
use Domain\Educator\Actions\Profile\UpdateAchievementsAction;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Achievements extends Component
{
    use WithFileUploads, LivewireAlert;

    public Educator $educator;
    public $achievements;
    public ?int $activeId = null;
    public bool $showAddForm = false;
    public array $formData = [];
    public $upload;
    public $existingFile = null;
    public array $achievementTypes = [];

    protected $listeners = ['executeDelete' => 'handleConfirmation'];

    protected function rules()
    {
        $type = $this->formData['type'] ?? null;
        $rules = [
            'formData.type' => 'required|in:work_experience,certificate,course,workshop',
            'formData.title' => 'required|string|max:255',
            'formData.organization' => 'required|string|max:255',
            'formData.start_date' => 'required|date',
            'formData.end_date' => ['nullable', 'date', 'after_or_equal:formData.start_date'],
            'formData.description' => 'nullable|string|max:1000',
            'upload' => 'nullable|file|max:5120',
        ];

        if ($type === 'work_experience') {
            $rules['formData.end_date'][] = 'required';
        }
        return $rules;
    }

    protected function validationAttributes()
    {
        return [
            'formData.type' => __('educator::builder.achievements.entry_type'),
            'formData.title' => __('educator::builder.achievements.title'),
            'formData.organization' => __('educator::builder.achievements.issuing_organization'),
            'formData.start_date' => __('educator::builder.achievements.issue_date'),
            'formData.end_date' => __('educator::builder.achievements.end_date'),
            'formData.description' => __('educator::builder.achievements.description'),
            'upload' => __('educator::builder.achievements.attachment'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadAchievements();
        $this->achievementTypes = [
            'work_experience' => __('educator::builder.achievements.work_experience'),
            'certificate' => __('educator::builder.achievements.certificate'),
            'course' => __('educator::builder.achievements.course'),
            'workshop' => __('educator::builder.achievements.workshop'),
        ];
    }

    public function loadAchievements()
    {
        $this->achievements = $this->educator->achievements()->with('file')->orderBy('start_date', 'desc')->get();
    }

    public function showAddForm()
    {
        $this->closeForm();
        $this->showAddForm = true;
        $this->formData = [
            'type' => 'certificate',
            'start_date' => now()->format('Y-m-d'),
            'title' => '', 'organization' => '', 'description' => '', 'end_date' => null,
        ];
    }

    public function selectForEditing($id)
    {
        if ($this->activeId === $id) {
            $this->closeForm();
            return;
        }
        $achievement = $this->achievements->find($id);
        if ($achievement) {
            $this->showAddForm = false;
            $this->activeId = $id;
            $this->formData = $achievement->toArray();
            foreach (['start_date', 'end_date'] as $field) {
                if (!empty($this->formData[$field])) {
                    $this->formData[$field] = \Illuminate\Support\Carbon::parse($this->formData[$field])->format('Y-m-d');
                }
            }
            $this->existingFile = $achievement->file;
            $this->upload = null;
            $this->resetErrorBag();
        }
    }

    public function closeForm()
    {
        $this->activeId = null;
        $this->showAddForm = false;
        $this->resetErrorBag();
        $this->upload = null;
        $this->existingFile = null;
    }

    public function save(UpdateAchievementsAction $updateAchievementsAction)
    {
        $validatedData = $this->validate();
        try {
            $updateAchievementsAction->handle($this->educator, $validatedData['formData'], $this->activeId, $this->upload);
            $this->loadAchievements();
            $this->closeForm();
            $this->alert('success', __('educator::builder.messages.achievement_saved'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function confirmRemoval($id)
    {
        $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_text'), 'removeAchievement', $id);
    }

    public function confirmImageRemoval()
    {
        if ($this->activeId) {
            $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_image_text'), 'removeExistingImage', $this->activeId);
        }
    }

    public function handleConfirmation(string $method, $params)
    {
        if ($method === 'removeAchievement') {
            $this->removeAchievement($params);
        }
        if ($method === 'removeExistingImage') {
            $this->removeExistingImage($params);
        }
    }

    public function removeAchievement($id)
    {
        $updateAchievementsAction = app(UpdateAchievementsAction::class);
        try {
            $updateAchievementsAction->delete($this->educator, $id);
            $this->loadAchievements();
            $this->closeForm();
            $this->alert('success', __('educator::builder.messages.achievement_deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function removeExistingImage($id)
    {
        $imageService = resolve(ImageService::class);
        $achievement = $this->educator->achievements()->find($id);
        if ($achievement && $achievement->file) {
            $imageService->destroyImg($achievement->file->path, $achievement->file->storage);
            $achievement->file->delete();
            $this->loadAchievements();
            $this->existingFile = null;
            $this->alert('success', __('educator::builder.messages.image_deleted_successfully'));
        }
    }

    public function saveAndNext()
    {
        $this->emitUp('stepCompleted', 6);
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.achievements');
    }
}
