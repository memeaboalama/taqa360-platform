<?php

namespace Application\Admin\Livewire\Educator\Profile;

use Domain\Educator\Models\Educator;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public string $activeTab = 'overview';
    public ?string $rejection_reason = null;

    protected $queryString = [
        'activeTab' => ['except' => 'overview', 'as' => 'tab'],
    ];

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load(['profile.city', 'files']);
    }

    public function changeStatus($newStatus)
    {
        $this->educator->status = $newStatus;
        $this->educator->save();
        $this->alert('success', __('admin::educators.status_updated'));
        $this->educator->refresh();
    }

    public function openRejectModal(): void
    {
        $this->rejection_reason = null;
        $this->resetValidation('rejection_reason');
        $this->emit('openModal', '#rejectEducatorModal');
    }

    public function closeRejectModal(): void
    {
        $this->rejection_reason = null;
        $this->resetValidation('rejection_reason');
        $this->emit('closeModal', '#rejectEducatorModal');
    }

    public function rejectAccount(): void
    {
        $this->validate(
            ['rejection_reason' => 'required|string|max:500'],
            ['rejection_reason.required' => __('admin::educators.rejection_reason_required')]
        );

        $this->educator->status = 'rejected';
        $this->educator->rejection_reason = $this->rejection_reason;
        $this->educator->save();
        $this->educator->refresh();

        $this->rejection_reason = null;
        $this->emit('closeModal', '#rejectEducatorModal');
        $this->alert('success', __('admin::educators.rejected_successfully'));
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.index');
    }
}
