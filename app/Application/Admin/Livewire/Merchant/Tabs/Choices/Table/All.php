<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Choices\Table;

use Carbon\Carbon;
use Domain\MarketPlace\Models\Choice;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $merchantId;

    protected $model = Choice::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return Choice::where('merchant_id', $this->merchantId)->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Title'), 'title')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("admin::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("admin::lang.$value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.merchant.tabs.choices.table.action')->withRow($row);
                })
        ];
    }
}
