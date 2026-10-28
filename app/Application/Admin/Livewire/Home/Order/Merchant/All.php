<?php

namespace Application\Admin\Livewire\Home\Order\Merchant;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;
class All extends DataTableComponent
{
    use TableConfiguration;

    public $filters = [];

    public $listeners = ['refreshOrders'];
    public function __construct()
    {
        parent::__construct();
    }

    public function refreshOrders(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return MerchantOrder::select([
            'id',
            'code',
            'merchant_id',
            'user_id',
            'merchant_branch_id',
            'total_after_vat',
            'status',
            'created_at'])->
            with(['merchant:company_name','user:name','branch:title'])->take(10);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.code'), 'code')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
                })
                ->searchable(),

            Column::make(__('admin::home.userName'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user?->name;
                })
                ->searchable(),

            Column::make(__('admin::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch?->title;
                })
                ->searchable(),

            Column::make(__('admin::home.totalServices'), 'total_after_vat')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) . ' SAR';
                })
                ->searchable(),


            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.order.table.action')->withRow($row);
                })
        ];
    }
}
