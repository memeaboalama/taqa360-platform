<?php

namespace Application\Marketplace\Livewire\AdditionGroup\Table;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Domain\MarketPlace\Models\AdditionGroup;
use Rappasoft\LaravelLivewireTables\Views\Column;

use Support\Traits\Datatables\TableConfiguration;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchProviderAction;

class All extends DataTableComponent
{
    use TableConfiguration;

    public $merchantId;
    protected $model = AdditionGroup::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function mountComponentUtilities()
    {
        $this->merchantId = resolve('_InitialMerchant');
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
                    return view('marketplace::livewire.additiongroup.table.action')->withRow($row);
                })
        ];
    }
}
