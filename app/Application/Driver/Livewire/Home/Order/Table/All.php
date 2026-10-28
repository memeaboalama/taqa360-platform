<?php

namespace Application\Driver\Livewire\Home\Order\Table;

use App\Application\Driver\Enums\DriverOrderStatuses;
use App\Domain\MarketPlace\Models\DriverOrder;
use Carbon\Carbon;
use Domain\MarketPlace\Models\DriverService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = DriverOrder::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return DriverOrder::with(['services','user'])->where('driver_id', resolve('_InitialDriver'))->take(10);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make(__('driver::home.userName'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user->name;
                })
                ->searchable(),

            Column::make(__('driver::home.Total Price'), 'total_after_vat')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) . ' SAR';
                })
                ->searchable(),


            Column::make(__('driver::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),


            Column::make(__('driver::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('driver::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('driver::livewire.home.order.table.action')->withRow($row);
                })
        ];
    }
}
