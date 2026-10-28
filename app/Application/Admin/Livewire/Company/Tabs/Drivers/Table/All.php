<?php

namespace Application\Admin\Livewire\Company\Tabs\Drivers\Table;

use App\Domain\Driver\Repositories\DriverRepository;
use Carbon\Carbon;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    public $companyId;
    protected DriverRepository $driverRepository;
    public $listeners = ['refreshDrivers'];
    public function __construct()
    {
        $this->driverRepository = new DriverRepository(new Driver());
        parent::__construct();
    }

    public function mount(int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function refreshDrivers(array $filters = [])
    {
        $this->filters = $filters;
        $this->filters['company_id'] = $this->companyId;
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
        return $this->driverRepository->filter($this->filters)
            ->where('company_id', $this->companyId);
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

            Column::make(__('admin::home.Email'), 'email')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Phone Number'), 'phone')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->PhoneNumber;
                })
                ->searchable(),


            Column::make( __('admin::home.Network Status'), 'network_status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">' . __("admin::lang.online") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("admin::lang.offline") . '</span>';
                    }
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

            Column::make(__('admin::home.files'), 'id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->filesCompleted()) {
                        echo '<span class="badge bg-success">'.__('admin::home.completed').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.Incomplete').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.driverStatus'), 'is_complete')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">'.__('admin::home.approved').'</span>';
                    } else {
                        echo '<span class="badge bg-warning">'.__('admin::home.pending').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.Elm status'), 'elm_upload_status')
                ->hideIf(true),

            Column::make(__('admin::home.Elm status'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.driver.table.elmaction')->withRow($row);
                })
                ->sortable()
                ->searchable(),    
                
            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.driver.table.action')->withRow($row);
                })

        ];
    }
}
