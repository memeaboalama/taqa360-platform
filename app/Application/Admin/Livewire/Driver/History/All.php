<?php

namespace Application\Admin\Livewire\Driver\History;

use App\Domain\Driver\Repositories\HistoryDriverRepository;
use Carbon\Carbon;
use App\Domain\MarketPlace\Models\DriverHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;
use Illuminate\Support\Facades\Log;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected HistoryDriverRepository $driverRepository;
    public $listeners = ['refreshDriverHistory'];

    public function __construct()
    {
        $this->driverRepository = new HistoryDriverRepository(new DriverHistory());
        parent::__construct();
    }

    public function refreshDriverHistory(array $filters = [])
    {
        $this->filters = $filters;
        $this->setBuilder($this->builder());
        // $this->emit('refreshDriverFilter');
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

                
            Column::make(__('admin::home.Name'), 'driver_id')
            ->sortable()
            ->format(function ($value, $row, Column $column) {
                echo $row->driver?->name;
            })
            ->searchable(),

                // Column::make(__('admin::home.Id'), 'driver_id')
                // ->sortable()
                // ->searchable(),

            Column::make(__('admin::home.Action'), 'action')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo __('admin::home.'.$row->action);
                })
                ->searchable(),
                


            Column::make(__('admin::home.Company Name'), 'driver_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->driver?->company?->company_name;
                })
                ->searchable(),

            // Column::make(__('admin::home.Phone Number'), 'phone')
            //     ->sortable()
            //     ->format(function ($value, $row, Column $column) {
            //         echo $row->PhoneNumber;
            //     })
            //     ->searchable(),


            // Column::make(__('admin::home.status'), 'status')
            //     ->sortable()
            //     ->format(function ($value, $row, Column $column) {
            //         if ($value->value == 'active') {
            //             echo '<div style="cursor: pointer;" wire:click="statusClickedDiver(' . $row->id . ')"><span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span></div>';
            //         } else {
            //             echo '<div style="cursor: pointer;" wire:click="statusClickedDiver(' . $row->id . ')"><span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span></div>';
            //         }
            //     })
            //     ->searchable(),


            // Column::make(__('admin::home.driverStatus'), 'is_complete')
            //    ->hideIf(true)
            //     ->sortable()
            //     ->format(function ($value, $row, Column $column) {
            //         if ($value) {
            //             echo '<span class="badge bg-success">'.__('admin::home.approved').'</span>';
            //         } else {
            //             echo '<span class="badge bg-warning">'.__('admin::home.pending').'</span>';
            //         }
            //     })
            //     ->searchable(),
                

    

            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value);
                })
                ->sortable()
                ->searchable(),

        ];
    }
}
