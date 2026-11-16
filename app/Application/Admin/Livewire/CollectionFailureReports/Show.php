<?php

namespace Application\Admin\Livewire\CollectionFailureReports;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReport;
use App\Domain\MarketPlace\Services\CollectionFailureWorkflow;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;

    public int $reportId;
    public string $instructions = '';

    public function mount(DriverCollectionFailureReport $report): void
    {
        $this->authorizeAccess();
        $this->reportId = $report->id;
    }

    public function requestRetry(CollectionFailureWorkflow $workflow): void
    {
        $this->decide($workflow, 'retry_requested');
    }

    public function requestReturn(CollectionFailureWorkflow $workflow): void
    {
        $this->decide($workflow, 'return_requested');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->authorizeAccess();
        return view('admin::livewire.collection-failure-reports.show', [
            'report' => DriverCollectionFailureReport::query()
                ->with(['driver', 'order', 'histories.actor', 'decider'])
                ->findOrFail($this->reportId),
        ]);
    }

    private function decide(CollectionFailureWorkflow $workflow, string $decision): void
    {
        if (!CollectionSetting::adminCan('review') && !CollectionSetting::adminCan('approve')) {
            abort(403);
        }

        $data = $this->validate([
            'instructions' => ['required', 'string', 'max:2000'],
        ]);
        $workflow->decide(
            $this->reportId,
            $decision,
            $data['instructions'],
            auth('admin')->user()
        );
        $this->alert('success', __('admin::home.collection_failure_decision_saved'));
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review') || CollectionSetting::adminCan('approve')),
            403
        );
    }
}
