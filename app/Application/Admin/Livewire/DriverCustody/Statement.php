<?php

namespace Application\Admin\Livewire\DriverCustody;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use Domain\Driver\Models\Driver;
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
            'canCorrect' => $canCorrect,
        ]);
    }
}
