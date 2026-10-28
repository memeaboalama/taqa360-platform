<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Groups\Table;

use Carbon\Carbon;
use Domain\MarketPlace\Models\AdditionGroup;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public $merchantId;
    protected $model = AdditionGroup::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function mount(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return AdditionGroup::where('merchant_id', $this->merchantId)->latest();
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
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),
            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.merchant.tabs.groups.table.action')->withRow($row);
                })
        ];
    }
}
