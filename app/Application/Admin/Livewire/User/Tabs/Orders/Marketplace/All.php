<?php

namespace Application\Admin\Livewire\User\Tabs\Orders\Marketplace;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public int $user_id;
    protected MerchantOrderRepository $merchantOrderRepository;

    public function __construct()
    {
        $this->merchantOrderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }

    public function mount($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantOrderRepository->getUserOrderById($this->user_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make('User Name', 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user->name;
                })
                ->searchable(),

            Column::make(__('admin::home.Merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
                })
                ->searchable(),

            Column::make(__('admin::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch->title;
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

            Column::make('actions', 'user_id')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.user.tabs.orders.marketplace.action')->withRow($row);
                })
        ];
    }
}
