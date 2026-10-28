<?php

namespace Application\Company\Livewire\Driver\Table;

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

    protected $model = Driver::class;
    public $filters = [];
    protected DriverRepository $driverRepository;
    public $listeners = ['refreshDrivers'];

    public function __construct()
    {
        $this->driverRepository = new DriverRepository(new Driver());
        parent::__construct();
    }

    public function refreshDrivers(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
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
            ->where(['company_id' => auth()->guard('company')->user()->id]);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Driver Name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Email Address'), 'email')
                ->sortable()
                ->searchable(),


            Column::make(__('company::home.Phone Number'), 'phone')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Network Status'), 'network_status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">' . __("company::home.online") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("company::home.offline") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.files'), 'id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->filesCompleted()) {
                        echo '<span class="badge bg-success">'.__('admin::home.completed').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.Incomplete').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.driverStatus'), 'is_complete')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">'.__('admin::home.approved').'</span>';
                    } else {
                        echo '<span class="badge bg-warning">'.__('admin::home.pending').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.STATUS'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("company::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("company::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.Elm status'), 'elm_upload_status')
                ->hideIf(true),

            Column::make(__('company::home.Elm status'), 'action')
                ->label(function ($row, Column $column) {
                    return view('company::livewire.driver.table.elmaction')->withRow($row);
                })
                ->sortable()
                ->searchable(),
    

            Column::make(__('company::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('company::livewire.driver.table.action')->withRow($row);
                })
        ];
    }
}
