<?php

namespace Application\Admin\Livewire\Driver\Pending\Table;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
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
    protected DriverRepository $driverRepository;
    public $listeners = ['refreshDrivers', 'refreshDatatable' => '$refresh'];
    public function __construct()
    {
        $this->driverRepository = new DriverRepository(new Driver());
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
        return $this->driverRepository->filter($this->filters)
            ->where('is_complete', 0)
            ->where('status', '!=', StatusEnum::REJECTED->value);
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


            Column::make(__('admin::home.Network Status'), 'network_status')
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
                    // $row->status is enum-cast and throws ValueError on unexpected legacy
                    // DB values, which would break the whole table. Fall back safely.
                    try {
                        $statusValue = $row->status?->value;
                    } catch (\Throwable $e) {
                        $statusValue = null;
                    }
                    if ($statusValue === 'active') {
                        echo '<span class="badge bg-success">' . __('marketplace::lang.active') . '</span>';
                    } elseif ($statusValue) {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.{$statusValue}") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __('admin::home.inactive') . '</span>';
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

