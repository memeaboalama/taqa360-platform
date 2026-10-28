<?php

namespace Application\Company\Livewire\Driver\Components;

use App\Application\Company\Exports\Drivers\DriverTotalExport;
use App\Domain\Driver\Repositories\DriverRepository;
use Domain\Driver\Models\Driver;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public int $driver_id;
    protected DriverRepository $driverRepository;
    protected $listeners = ['export'];

    public function mount(int $id)
    {
        $this->driver_id = $id;
    }

    public function export()
    {
        $this->driverRepository = new DriverRepository(new Driver());
        return Excel::download(new DriverTotalExport($this->driverRepository->getDriverTotalOrderReport($this->driver_id))
            , __('company::home.Driver Orders Report') .'.xlsx');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.driver.components.export');
    }
}