<?php

namespace Application\Driver\Livewire\Settlement;

use App\Domain\MarketPlace\Models\OrderSettlement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class MySettlements extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    protected $listeners = ['refreshMySettlements' => '$refresh'];

    protected $paginationTheme = 'bootstrap';

    public function applyFilters(): void
    {
        $this->validate([
            'statusFilter' => ['nullable', Rule::in(['pending_review', 'reviewed', 'returned', 'rejected', 'approved'])],
            'dateFrom' => ['nullable', 'date_format:Y-m-d'],
            'dateTo' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:dateFrom'],
        ]);

        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->resetValidation();
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        $query = OrderSettlement::query()
            ->with(['order', 'histories'])
            ->where('driver_id', $driverId)
            ->where(function ($query) {
                $query->whereNull('batch_id')->orWhereColumn('batch_id', 'id');
            });

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom !== '' || $this->dateTo !== '') {
            $query->where(function ($query) use ($driverId) {
                $query->whereHas('histories', function ($historyQuery) {
                    $historyQuery->whereIn('action', ['submitted', 'resubmitted']);
                    if ($this->dateFrom !== '') {
                        $historyQuery->whereDate('created_at', '>=', $this->dateFrom);
                    }
                    if ($this->dateTo !== '') {
                        $historyQuery->whereDate('created_at', '<=', $this->dateTo);
                    }
                })->orWhere(function ($legacyQuery) use ($driverId) {
                    $legacyQuery->where('driver_id', $driverId)->whereDoesntHave('histories');
                    if ($this->dateFrom !== '') {
                        $legacyQuery->whereDate('created_at', '>=', $this->dateFrom);
                    }
                    if ($this->dateTo !== '') {
                        $legacyQuery->whereDate('created_at', '<=', $this->dateTo);
                    }
                });
            });
        }

        return view('driver::livewire.settlement.my-settlements', [
            'settlements' => $query
                ->latest('id')
                ->paginate(25)
                ->through(function (OrderSettlement $settlement) {
                    $members = OrderSettlement::batchQuery($settlement->batch_number)
                        ->with(['order', 'histories'])
                        ->get();
                    $settlement->setAttribute('batch_amount', round((float) $members->sum('amount'), 2));
                    $settlement->setAttribute(
                        'batch_orders',
                        $members->pluck('order.code')->filter()->values()->all()
                    );
                    $settlement->setAttribute(
                        'batch_latest_history',
                        $members->flatMap->histories->sortBy('created_at')->last()
                    );

                    return $settlement;
                }),
        ]);
    }
}
