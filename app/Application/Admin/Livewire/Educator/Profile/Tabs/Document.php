<?php

namespace Application\Admin\Livewire\Educator\Profile\Tabs;

use Domain\Educator\Models\Educator;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Document extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public $profile;
    public $identityFiles;
    public $otherFiles;

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $this->educator->load('profile', 'files');
        $this->profile = $this->educator->profile;

        $this->identityFiles = $this->educator->files()
            ->whereIn('collection_name', ['identity_front', 'identity_back', 'passport_image'])->get();

        $this->otherFiles = $this->educator->files()
            ->whereNotIn('collection_name', ['identity_front', 'identity_back', 'passport_image', 'profile_image'])->get();
    }

    public function setIdentityStatus(string $status)
    {
        try {
            if ($this->profile) {
                $this->profile->identity_status = $status;
                $this->profile->save();
                $this->alert('success', __('admin::educators.identity_updated'));
            } else {
                $this->alert('error', __('admin::educators.identity_update_failed'));
            }
        } catch (\Exception $e) {
            $this->alert('error', __('admin::educators.identity_update_failed'));
        }
    }

    public function getCollectionLabel(string $collectionName): string
    {
        $labels = [
            'identity_front' => __('admin::educators.identity_front'),
            'identity_back' => __('admin::educators.identity_back'),
            'passport_image' => __('admin::educators.passport_image'),
            'profile_image' => __('admin::educators.profile_image'),
            'general_document' => __('admin::educators.general_document'),
            'passport' => __('admin::educators.document_passport'),
            'professional_license' => __('admin::educators.document_professional_license'),
            'other' => __('admin::educators.document_other'),
        ];

        if (isset($labels[$collectionName])) {
            return $labels[$collectionName];
        }

        return $collectionName ? str_replace('_', ' ', ucfirst($collectionName)) : __('admin::educators.general_document');
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.tabs.document');
    }
}
