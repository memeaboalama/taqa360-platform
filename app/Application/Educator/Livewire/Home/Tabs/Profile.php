<?php
namespace Application\Educator\Livewire\Home\Tabs;

use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Profile extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public bool $editMode = false;

    public $name;
    public $specialization;
    public $city_id;
    public $experience_years;
    public $description;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'city_id' => 'nullable|integer|exists:cities,id',
            'experience_years' => 'required|integer|min:0',
            'description' => 'nullable|string|min:150|max:2000',
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load(['profile', 'bios', 'skills.approvedSkill', 'languages.language']);
        $this->syncDataFromModel();
    }

    public function syncDataFromModel()
    {
        $this->name = $this->educator->name;
        $this->specialization = $this->educator->profile?->specialization;
        $this->city_id = $this->educator->profile?->city_id;
        $this->experience_years = $this->educator->profile?->experience_years;
        $this->description = $this->educator->bios()->firstOrCreate(['educator_id' => $this->educator->id])->description;
    }

    public function toggleEditMode()
    {
        if ($this->editMode) {
            $this->save();
        } else {
            $this->syncDataFromModel();
        }
        $this->editMode = !$this->editMode;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->educator->update(['name' => $this->name]);

            $this->educator->profile()->updateOrCreate(
                ['educator_id' => $this->educator->id],
                [
                    'specialization' => $this->specialization,
                    'city_id' => $this->city_id,
                    'experience_years' => $this->experience_years,
                ]
            );

            $this->educator->bios()->updateOrCreate(
                ['educator_id' => $this->educator->id],
                ['description' => $this->description]
            );
        });

        $this->editMode = false;
        $this->alert('success', __('educator::builder.messages.success_updated_profile'));
    }

    public function render()
    {
        return view('educator::livewire.home.tabs.profile');
    }
}
