<?php

namespace Application\Admin\Livewire\DriverCustody;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;

class Statement extends Component
{
    use WithPagination;
    use LivewireAlert;

    public int $driverId;
    public string $from = '';
    public string $to = '';
    public string $movementType = '';
    public string $orderSearch = '';
    public string $settlementSearch = '';
    public string $correctionSettlementId = '';
    public string $correctionDirection = 'debit';
    public string $correctionAmount = '';
    public string $correctionReason = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(Driver $driver): void
    {
        $this->driverId = $driver->id;
        $filters = Validator::make(request()->query(), [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'type' => ['nullable', 'in:collection,settlement,correction,refund'],
            'order' => ['nullable', 'string', 'max:100'],
            'settlement' => ['nullable', 'string', 'max:100'],
        ])->validate();

        $this->from = (string) ($filters['from'] ?? '');
        $this->to = (string) ($filters['to'] ?? '');
        $this->movementType = (string) ($filters['type'] ?? '');
        $this->orderSearch = (string) ($filters['order'] ?? '');
        $this->settlementSearch = (string) ($filters['settlement'] ?? '');
    }

    public function applyFilters(): void
    {
        $this->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'movementType' => ['nullable', 'in:collection,settlement,correction,refund'],
            'orderSearch' => ['nullable', 'string', 'max:100'],
            'settlementSearch' => ['nullable', 'string', 'max:100'],
        ]);

        if ($this->from && $this->to && $this->from > $this->to) {
            $this->addError('from', __('admin::home.from_date_must_precede_to_date'));
            return;
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('from', 'to', 'movementType', 'orderSearch', 'settlementSearch');
        $this->resetPage();
    }

    public function addCorrection(): void
    {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $data = $this->validate([
            'correctionSettlementId' => [
                'required',
                'integer',
                'exists:order_settlements,id',
            ],
            'correctionDirection' => ['required', 'in:credit,debit'],
            'correctionAmount' => ['required', 'numeric', 'gt:0', 'max:10000000'],
            'correctionReason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data): void {
            $settlement = OrderSettlement::query()
                ->where('driver_id', $this->driverId)
                ->whereKey($data['correctionSettlementId'])
                ->lockForUpdate()
                ->firstOrFail();

            $amount = round((float) $data['correctionAmount'], 2);
            $settlement->corrections()->create([
                'corrected_by' => auth('admin')->id(),
                'amount' => $data['correctionDirection'] === 'debit' ? -$amount : $amount,
                'reason' => $data['correctionReason'],
                'created_at' => now(),
            ]);
        });

        $this->reset('correctionSettlementId', 'correctionAmount', 'correctionReason');
        $this->correctionDirection = 'debit';
        $this->resetPage();
        $this->alert('success', __('admin::home.correction_saved'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if (auth('driver')->check()) {
            $this->driverId = (int) auth('driver')->id();
        } else {
            $admin = auth('admin')->user();
            abort_unless($admin, 403);
            setPermissionsTeamId(1);
            abort_unless(
                $admin->type === 'admin'
                    || $admin->hasAnyPermission(['admin', 'show delivery fees_settings', 'edit delivery fees_settings'])
                    || CollectionSetting::adminCan('view'),
                403
            );
        }

        $driver = Driver::query()->findOrFail($this->driverId);
        $canCorrect = auth('admin')->check() && CollectionSetting::adminCan('correct');

        return view('admin::livewire.driver-custody.statement', [
            'driver' => $driver,
            'statement' => app(DriverCustodyLedger::class)->statement($driver->id, [
                'from' => $this->from,
                'to' => $this->to,
                'type' => $this->movementType,
                'order' => $this->orderSearch,
                'settlement' => $this->settlementSearch,
            ]),
            'correctionSettlements' => $canCorrect
                ? OrderSettlement::query()
                    ->with('order')
                    ->where('driver_id', $driver->id)
                    ->latest('id')
                    ->get()
                : collect(),
            'canCorrect' => $canCorrect,
        ]);
    }
}
