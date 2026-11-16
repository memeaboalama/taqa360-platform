<?php

namespace Application\Admin\Livewire\DriverCustody;

use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use App\Domain\MarketPlace\Models\CollectionSetting;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        setPermissionsTeamId(1);
        abort_unless(
            $admin->type === 'admin'
                || $admin->hasAnyPermission(['admin', 'show delivery fees_settings', 'edit delivery fees_settings'])
                || CollectionSetting::adminCan('view'),
            403
        );

        return view('admin::livewire.driver-custody.index', [
            'drivers' => app(DriverCustodyLedger::class)->driverSummaries(
                trim($this->search) !== '' ? trim($this->search) : null
            ),
        ]);
    }
}
