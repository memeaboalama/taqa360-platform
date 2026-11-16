<?php

namespace Application\Driver\Livewire\Settlement;

use App\Domain\MarketPlace\Models\OrderSettlement;
use Livewire\Component;
use Livewire\WithPagination;

class MySettlements extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render(): \Illuminate\Contracts\View\View
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        return view('driver::livewire.settlement.my-settlements', [
            'settlements' => OrderSettlement::query()
                ->with(['order', 'histories'])
                ->where('driver_id', $driverId)
                ->latest('id')
                ->paginate(25),
        ]);
    }
}
