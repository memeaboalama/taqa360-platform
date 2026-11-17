<?php

namespace Application\Admin\Livewire\SettlementReview;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $driverId = '';
    public string $from = '';
    public string $to = '';
    public string $status = 'pending_review';

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->authorizeAccess();
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'driverId', 'from', 'to', 'status'], true)) {
            $rules = [
                'search' => ['nullable', 'string', 'max:100'],
                'driverId' => ['nullable', 'integer', 'exists:drivers,id'],
                'from' => ['nullable', 'date_format:Y-m-d'],
                'to' => ['nullable', 'date_format:Y-m-d'],
                'status' => ['nullable', 'in:pending_review,reviewed,returned,rejected,approved'],
            ];
            $this->validateOnly($property, $rules);
            $this->resetPage();
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->authorizeAccess();
        $this->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'driverId' => ['nullable', 'integer', 'exists:drivers,id'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['nullable', 'in:pending_review,reviewed,returned,rejected,approved'],
        ]);

        $settlements = OrderSettlement::query()
            ->with(['driver', 'order', 'histories'])
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);
                $settlementId = preg_replace('/^STL-/i', '', $search);

                $query->where(function ($query) use ($search, $settlementId) {
                    if (ctype_digit($settlementId)) {
                        $query->where('id', (int) $settlementId);
                    }
                    $query->orWhereHas('order', function ($query) use ($search) {
                        $query->where('code', 'like', '%' . $search . '%')
                            ->orWhere('id', $search);
                    });
                });
            })
            ->when($this->driverId !== '', fn ($query) => $query->where('driver_id', $this->driverId))
            ->when($this->from !== '', fn ($query) => $query->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn ($query) => $query->whereDate('created_at', '<=', $this->to))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(25);

        return view('admin::livewire.settlement-review.index', [
            'settlements' => $settlements,
            'drivers' => \Domain\Driver\Models\Driver::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review')
                    || CollectionSetting::adminCan('approve')
                    || CollectionSetting::adminCan('correct')),
            403
        );
    }
}
