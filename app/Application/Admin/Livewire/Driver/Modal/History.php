<?php

namespace Application\Admin\Livewire\Driver\Modal;

use App\Domain\MarketPlace\Models\DriverHistory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
class History extends Component
{
    use LivewireAlert;

    public  $driver_history ;
    protected $listeners = ['showDriverHistory'];
    public int $dr_id;
    public function showDriverHistory($driver_id): void
    {
        $this->dr_id=$driver_id;
        $this->driver_history = DriverHistory::select('action','created_at')
        ->where('driver_id', $this->dr_id)
        ->whereDay('created_at', now()->day)
        ->get();
        $this->emit('openModal', '#showDriverHistory');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.history');
    }
}
