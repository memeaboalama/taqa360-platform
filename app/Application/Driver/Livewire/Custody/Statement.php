<?php

namespace Application\Driver\Livewire\Custody;

use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use Livewire\Component;
use Livewire\WithPagination;

class Statement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshDriverCustodyStatement' => '$refresh'];

    protected $paginationTheme = 'bootstrap';

    public string $from = '';
    public string $to = '';
    public string $orderSearch = '';
    public string $settlementStatus = '';
    public array $movementDetails = [];

    public function mount(): void
    {
        abort_unless(auth('driver')->id(), 403);
    }

    public function applyFilters(): void
    {
        $this->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'orderSearch' => ['nullable', 'string', 'max:100'],
            'settlementStatus' => ['nullable', 'in:not_submitted,pending_review,reviewed,approved,returned,rejected'],
        ]);

        if ($this->from && $this->to && $this->from > $this->to) {
            $this->addError('from', __('driver::home.custody_from_date_must_precede_to_date'));
            return;
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('from', 'to', 'orderSearch', 'settlementStatus');
        $this->resetPage();
    }

    public function viewMovement(string $movementType, int $recordId): void
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        $movement = app(DriverCustodyLedger::class)->movement(
            (int) $driverId,
            $movementType,
            $recordId
        );
        abort_unless($movement, 404);

        $this->movementDetails = (array) $movement;
        $this->emit('openModal', '#driverCustodyMovementModal');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        return view('driver::livewire.custody.statement', [
            'statement' => app(DriverCustodyLedger::class)->statement((int) $driverId, [
                'from' => $this->from,
                'to' => $this->to,
                'order' => $this->orderSearch,
                'settlement_status' => $this->settlementStatus,
            ]),
        ]);
    }
}
