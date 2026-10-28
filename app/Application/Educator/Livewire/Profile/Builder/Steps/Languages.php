<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use App\Domain\AppBase\Models\Setting\Language;
use Domain\Educator\Actions\Profile\UpdateLanguagesAction;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Languages extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public $educatorLanguages;
    public $languageOptions;
    public array $levelOptions = [];

    public bool $showForm = false;
    public ?int $editingId = null;
    public array $formData = [];

    protected $listeners = ['executeDelete' => 'handleConfirmation'];

    protected function rules()
    {
        return [
            'formData.language_id' => 'required|exists:languages,id',
            'formData.level' => 'required|string|in:' . implode(',', array_keys($this->levelOptions)),
            'formData.is_primary' => 'boolean',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'formData.language_id' => __('educator::builder.languages.language'),
            'formData.level' => __('educator::builder.languages.level'),
            'formData.is_primary' => __('educator::builder.languages.is_primary'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->levelOptions = [
            'beginner' => __('educator::builder.languages.levels.beginner'),
            'intermediate' => __('educator::builder.languages.levels.intermediate'),
            'advanced' => __('educator::builder.languages.levels.advanced'),
            'native' => __('educator::builder.languages.levels.native'),
        ];
        $this->loadLanguages();
    }

    public function loadLanguages()
    {
        $this->educatorLanguages = $this->educator->languages()->with('language')->get();
        $this->languageOptions = Language::where('status', 'active')->get();
    }

    public function resetForm()
    {
        $this->formData = ['language_id' => '', 'level' => '', 'is_primary' => false];
        $this->editingId = null;
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function showAddForm()
    {
        $this->resetForm();
        if ($this->educatorLanguages->isEmpty()) {
            $this->formData['is_primary'] = true;
        }
        $this->showForm = true;
    }

    public function edit($id)
    {
        if ($this->editingId === $id) {
            $this->resetForm();
            return;
        }
        $language = $this->educatorLanguages->find($id);
        if ($language) {
            $this->formData = $language->toArray();
            $this->editingId = $id;
            $this->showForm = false; // Keep add form closed
            $this->resetErrorBag();
        }
    }

    public function saveLanguage(UpdateLanguagesAction $updateLanguagesAction)
    {
        $this->validate();
        try {
            $updateLanguagesAction->handle($this->educator, $this->formData, $this->editingId);
            $this->loadLanguages();
            $this->resetForm();
            $this->alert('success', __('educator::builder.messages.language_saved'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function confirmLanguageRemoval($id)
    {
        $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_text'), 'removeLanguage', $id);
    }

    public function handleConfirmation(string $method, $params)
    {
        if ($method === 'removeLanguage') {
            $this->removeLanguage($params);
        }
    }

    public function removeLanguage($id)
    {
        $updateLanguagesAction = app(UpdateLanguagesAction::class);

        try {
            $updateLanguagesAction->delete($this->educator, $id);
            $this->loadLanguages();
            $this->resetForm();
            $this->alert('success', __('educator::builder.messages.language_deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function saveAndNext()
    {
        if ($this->educatorLanguages->isEmpty()) {
            $this->addError('general', __('educator::builder.languages.error_at_least_one'));
            return;
        }
        if ($this->educatorLanguages->where('is_primary', true)->count() !== 1) {
            $this->addError('general', __('educator::builder.languages.error_one_primary'));
            return;
        }
        $this->resetForm();
        $this->emitUp('stepCompleted', 3);
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.languages');
    }
}
