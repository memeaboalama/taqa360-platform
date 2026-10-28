<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Orders;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Merchant;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public int $merchant_id;
    protected $model = Merchant::class;
    protected MerchantOrderRepository $merchantOrderRepository;

    public function __construct()
    {
        $this->merchantOrderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }

    public function mount($merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantOrderRepository->getMerchantOrderById($this->merchant_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [


            Column::make('User Name', 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user->name;
                })
                ->searchable(),


            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Total Amount', 'total_service')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->searchable(),


            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),


            Column::make('Date', 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make('actions', 'merchant_id')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.merchant.tabs.orders.action')->withRow($row);
                })
        ];
    }
}
