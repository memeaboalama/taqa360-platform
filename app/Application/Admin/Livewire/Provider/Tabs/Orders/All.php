<?php

namespace Application\Admin\Livewire\Provider\Tabs\Orders;

use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public int $provider_id;
    protected $model = ProviderOrder::class;
    protected ProviderOrderRepository $providerOrderRepository;

    public function __construct()
    {
        $this->providerOrderRepository = new ProviderOrderRepository(new ProviderOrder());
        parent::__construct();
    }

    public function mount($provider_id): void
    {
        $this->provider_id = $provider_id;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->providerOrderRepository->getProviderOrdersById($this->provider_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [


            Column::make('Client Name', 'client_name')
                ->sortable()
                ->searchable(),

//            Column::make('Provider Name', 'provider_name')
//                ->sortable()
//                ->searchable(),


            Column::make('Client Phone', 'client_full_phone')
                ->sortable()
                ->searchable(),

            Column::make('vehicle type', 'provider_vehicle_type')
                ->sortable()
                ->searchable(),


            Column::make('total', 'total_order')
                ->sortable()
                ->searchable(),


            Column::make('Date', 'date')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y') . " | \n";
                    echo Carbon::parse($row?->from)->format('g:i A') .
                        ' : ' . Carbon::parse($row?->to)->format('g:i A');
                })
                ->searchable(),


            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),

            Column::make('actions', 'merchant_id')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.company.tabs.orders.action')->withRow($row);
                })
        ];
    }
}
