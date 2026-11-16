<?php

namespace Application\Admin\Livewire\CollectionFailureReports;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReport;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $driverId = '';
    public string $status = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->authorizeAccess();
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'driverId', 'status'], true)) {
            $this->validateOnly($property, [
                'search' => ['nullable', 'string', 'max:100'],
                'driverId' => ['nullable', 'integer', 'exists:drivers,id'],
                'status' => ['nullable', 'in:open,retry_requested,return_requested,collection_succeeded,return_completed'],
            ]);
            $this->resetPage();
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->authorizeAccess();
        $this->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'driverId' => ['nullable', 'integer', 'exists:drivers,id'],
            'status' => ['nullable', 'in:open,retry_requested,return_requested,collection_succeeded,return_completed'],
        ]);

        $reports = DriverCollectionFailureReport::query()
            ->with(['driver', 'order'])
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);
                $reportId = preg_replace('/^CFR-/i', '', $search);
                $query->where(function ($query) use ($search, $reportId) {
                    if (ctype_digit($reportId)) {
                        $query->where('id', (int) $reportId);
                    }
                    $query->orWhereHas('order', fn ($query) => $query
                        ->where('code', 'like', '%' . $search . '%')
                        ->orWhere('id', $search));
                });
            })
            ->when($this->driverId !== '', fn ($query) => $query->where('driver_id', $this->driverId))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(25);

        return view('admin::livewire.collection-failure-reports.index', [
            'reports' => $reports,
            'drivers' => \Domain\Driver\Models\Driver::query()->orderBy('name')->get(['id', 'name']),
        ]);
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
