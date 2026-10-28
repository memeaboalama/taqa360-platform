<?php

namespace Application\Educator\Livewire\Profile\Builder\Steps;

use Domain\Educator\Actions\Profile\UpdateBiographyAction;
use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Biography extends Component
{
    use WithFileUploads, LivewireAlert;

    public Educator $educator;
    public array $bio = [];
    public array $socialLinks = [];
    public $profileImage;
    public $existingProfileImage = null;
    public array $platformOptions = [];

    protected function rules()
    {
        return [
            'bio.description' => 'required|string|min:150|max:2000',
            'socialLinks' => 'array|max:5',
            'socialLinks.*.platform' => 'required_with:socialLinks.*.url|string',
            'socialLinks.*.url' => 'required_with:socialLinks.*.platform|url',
            'profileImage' => 'nullable|image|max:5120',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'bio.description' => __('educator::builder.biography.biography'),
            'socialLinks.*.platform' => __('educator::builder.biography.platform'),
            'socialLinks.*.url' => __('educator::builder.biography.profile_url'),
            'profileImage' => __('educator::builder.biography.profile_image'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load('bios', 'socialLinks');
        $this->fillData();
        $this->platformOptions = [
            'facebook' => 'Facebook', 'twitter' => 'Twitter/X', 'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'website' => __('educator::builder.biography.website')
        ];
    }

    public function fillData()
    {
        $this->bio = $this->educator->bios()->firstOrCreate(['educator_id' => $this->educator->id])->toArray();
        $this->socialLinks = $this->educator->socialLinks->toArray();
        $this->existingProfileImage = $this->educator->profileImage;
    }

    public function addSocialLink()
    {
        if (count($this->socialLinks) < 5) {
            $this->socialLinks[] = ['platform' => '', 'url' => ''];
        }
    }

    public function removeSocialLink($index)
    {
        unset($this->socialLinks[$index]);
        $this->socialLinks = array_values($this->socialLinks);
    }

    public function save(UpdateBiographyAction $updateBiographyAction)
    {
        $this->validate();
        try {
            $updateBiographyAction->handle($this->educator, $this->bio, $this->socialLinks, $this->profileImage);
            $this->educator->refresh();
            $this->fillData();
            $this->profileImage = null;
            $this->alert('success', __('educator::builder.messages.success_updated_biography'));
        } catch (\Exception $e) {
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function saveAndNext()
    {
        $this->save(resolve(UpdateBiographyAction::class));
        if (!$this->getErrorBag()->any()) {
            $this->emitUp('stepCompleted', 7);
        }
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.biography');
    }
}
