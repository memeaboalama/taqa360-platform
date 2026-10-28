<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use Domain\AppBase\Models\Edu\Skill;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Collection;

class Skills extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public array $educatorSkills = [];
    public string $search = '';
    public Collection $searchResults;

    protected $listeners = ['executeDelete'];

    protected function rules()
    {
        return [
            'educatorSkills.*.level' => 'required|integer|min:1|max:5',
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadSkills();
        $this->searchResults = collect();
    }

    public function loadSkills()
    {
        $this->educatorSkills = $this->educator->skills()->with('approvedSkill')->get()->map(function ($skill) {
            return [
                'id' => $skill->id,
                'skill_id' => $skill->skill_id,
                'custom_name' => $skill->custom_name,
                'is_custom' => $skill->is_custom,
                'level' => $skill->level,
                'name' => $skill->is_custom ? $skill->custom_name : $skill->approvedSkill?->name,
            ];
        })->toArray();
    }

    public function updatedSearch($query)
    {
        if (strlen($query) < 2) {
            $this->showSuggestions();
            return;
        }

        $addedSkillIds = collect($this->educatorSkills)->whereNotNull('skill_id')->pluck('skill_id');

        $this->searchResults = Skill::where('name', 'like', '%' . $query . '%')
            ->where('status', 'active')->whereNotIn('id', $addedSkillIds)->take(7)->get();
    }

    public function showSuggestions()
    {
        $addedSkillIds = collect($this->educatorSkills)->whereNotNull('skill_id')->pluck('skill_id');
        $this->searchResults = Skill::where('status', 'active')
            ->whereNotIn('id', $addedSkillIds)
            ->latest()->take(7)->get();
    }

    public function addSkill($skillId)
    {
        $this->educator->skills()->create(['skill_id' => $skillId, 'is_custom' => false, 'level' => 3]);
        $this->resetSearch();
        $this->loadSkills();
        $this->alert('success', __('educator::builder.messages.skill_saved'));
    }

    public function addCustomSkill()
    {
        $this->validate(['search' => 'required|string|min:2|max:255']);
        if (collect($this->educatorSkills)->where('is_custom', true)->where('custom_name', $this->search)->isNotEmpty()) {
            $this->addError('search', 'هذه المهارة المخصصة مضافة بالفعل.');
            return;
        }
        $this->educator->skills()->create(['is_custom' => true, 'custom_name' => $this->search, 'level' => 3]);
        $this->resetSearch();
        $this->loadSkills();
        $this->alert('success', __('educator::builder.messages.skill_saved'));
    }

    public function confirmSkillRemoval($skillId)
    {
        $this->emitUp('showDeleteConfirmation',
            trans('educator::builder.confirm_delete_title'),
            trans('educator::builder.confirm_delete_text'),
            'removeSkill',
            $skillId
        );
    }

    public function executeDelete(string $method, $params)
    {
        if ($method === 'removeSkill') {
            $this->removeSkill($params);
        }
    }

    public function removeSkill($skillId)
    {
        $this->educator->skills()->find($skillId)?->delete();
        $this->loadSkills();
    }

    public function updatedEducatorSkills($level, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'level') {
            $index = $parts[0];
            $skillId = $this->educatorSkills[$index]['id'] ?? null;
            if ($skillId) {
                $this->educator->skills()->find($skillId)?->update(['level' => $level]);
            }
        }
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->searchResults = collect();
        $this->resetErrorBag();
    }

    public function saveAndNext()
    {
        $this->emitUp('stepCompleted', 5);
    }

    public function getLevelLabel(int $level): string
    {
        return match ($level) {
            1 => trans('مبتدئ'), 2 => trans('أساسي'), 3 => trans('متوسط'),
            4 => trans('متقدم'), 5 => trans('خبير'), default => '',
        };
    }
    public function render()
    {
        return view('educator::livewire.profile.builder.steps.skills');
    }
}
