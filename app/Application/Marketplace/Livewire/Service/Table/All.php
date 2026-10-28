<?php

namespace Application\Marketplace\Livewire\Service\Table;

use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = MerchantService::class;

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
        return MerchantService::with(['currency'])
            ->where('merchant_id', auth('merchant')->user()->merchant_id);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value") . '</span>';
                    }
                })
                ->searchable(),


            Column::make(__('marketplace::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j Y F');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.service.table.action')->withRow($row);
                })
        ];
    }
}
