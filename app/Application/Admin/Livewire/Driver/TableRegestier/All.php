<?php

namespace Application\Admin\Livewire\Driver\TableRegestier;

use App\Domain\Provider\Repositories\DriversRegisters\DriversRegistersRepository;
use Carbon\Carbon;
use App\Domain\Provider\Models\DriversRegisters;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected DriversRegistersRepository $driverRepository;
    public $listeners = ['refreshDrivers'];
    public function __construct()
    {
        $this->driverRepository = new DriversRegistersRepository(new DriversRegisters());
        parent::__construct();
    }

    public function refreshDrivers(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('prepareDriverOrderExport', $this->filters);
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->driverRepository->filter($this->filters);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Name'), 'name')
                ->sortable()
                ->searchable(),

            // Column::make(__('admin::home.Email'), 'email')
            //     ->sortable()
            //     ->searchable(),



            Column::make(__('admin::home.Phone Number'), 'phone')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->PhoneNumber;
                })
                ->searchable(),


         

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),          
        
    

            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),
            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.driver.tableregestier.action')->withRow($row);
                })

        ];
    }
}
