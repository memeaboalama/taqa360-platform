<?php

namespace Application\Marketplace\Livewire\Employee\Table;

use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{

    use TableConfiguration;
    public $merchantId;

    protected $model = MerchantEmployee::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function mountComponentUtilities()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function builder(): Builder
    {
        return MerchantEmployee::where('merchant_id', $this->merchantId)->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.Email Address'), 'email')
                ->sortable()
                ->searchable(),


            Column::make(__('marketplace::home.Phone Number'), 'phone')
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.ROLE NAME'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    $role_name = (strstr($row?->roles()?->first()?->name , '_', true)) ? strstr($row?->roles()?->first()?->name , '_', true) : $row?->roles()?->first()?->name;
                    echo '<span class="badge bg-success">'. $role_name .'</span>';
                })
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
                    return view('marketplace::livewire.employee.table.action')->withRow($row);
                })
        ];
    }

}
