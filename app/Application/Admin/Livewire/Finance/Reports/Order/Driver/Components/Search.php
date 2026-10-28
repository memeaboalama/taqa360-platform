<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Driver\Components;

use App\Domain\Driver\Repositories\DriverRepository;
use Domain\Driver\Models\Driver;
use Livewire\Component;

class Search extends Component
{
    public $query;
    public $results;
    public $highlightIndex;
    protected DriverRepository $driverRepository;

    protected $listeners = ['resetSearch', 'selectDriver'];

    public function mount()
    {
        $this->resetSearch();
    }

    public function resetSearch()
    {
        $this->query = '';
        $this->results = collect();
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->results) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->results) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function selectDriver($driver_id = null)
    {
        $driver = $this->results->where('id', $driver_id)->first() ?? null;
        $this->query = $driver->name . "(" . $driver->phone . ")";
        $this->highlightIndex = $this->results->search(function($result) use($driver) {
            return $result->id === $driver->id;
        });
        if ($driver) {
            $this->emit('refreshDriverFilter', $driver);
        }
    }

    public function updatedQuery()
    {
        $this->driverRepository = new DriverRepository(new Driver());
        $this->results = $this->driverRepository->search(['id', 'name', 'phone'],$this->query);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.finance.reports.driver.merchant.components.search');
    }
}
