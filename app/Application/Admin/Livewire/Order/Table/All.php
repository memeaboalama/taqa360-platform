<?php

namespace Application\Admin\Livewire\Order\Table;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;
class All extends DataTableComponent
{
    use TableConfiguration;

    public $filters = [];
    protected $model = MerchantOrder::class;
    protected MerchantOrderRepository $orderRepository;
    public $listeners = ['refreshOrders', 'exportExcel'];
    public function __construct()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchStatus(false);
    }


    public function refreshOrders(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('prepareMerchantOrderExport', $this->filters);
        $this->emit('refreshDatatable');
    }
    public function statusClicked($orderId)
    {
        $this->emit('showOrderHistory', $orderId);
    }
    

    public function builder(): Builder
    {
        return $this->orderRepository->filterOrders($this->filters, ['user']);
        
    }
   
    public function columns(): array
    {
        
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.code'), 'code')
            ->format(function ($value, $row, Column $column) {
                echo '<div > <span class="badge ' .$value. '">' . $value .  '</span> </div>';
            })
            ->hideIf(true)
                ->sortable()
                ->searchable(),

                Column::make(__('admin::home.Order No'), 'mark')
                ->format(function ($value, $row, Column $column) {
                    echo '<div> <span class=" ' . $row->mark?->color() . '-transparent">' . $row->code . '</span> </div>';
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.userName'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user->name;
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch?->title;
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.Promocode'), 'promocode')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row?->promocode ?? "---";
                })
                ->searchable(),
            Column::make(__('admin::home.discount'), 'discount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) ;
                })
                ->searchable(),  
                
            Column::make(__('admin::home.delivery_fee'), 'delivery_fee')
                ->hideIf(true)
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->searchable(),        
            Column::make(__('admin::home.totalServices'), 'subtotal')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value+$row->delivery_fee, 2) . ' SAR';
                })
                ->searchable(),

                Column::make(__('admin::home.Payment Status'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->paymentStatus == 'paid') {
                        echo '<span class="badge bg-success">' . __("admin::home.Payment Status paid") . '</span>';
                    } else if ($row->paymentStatus == 'initiated') {
                        echo '<span class="badge bg-primary">' . __("admin::home.Payment initiated") . '</span>';
                    }
                    else if ($row->paymentStatus == 'failed')
                    {
                        echo '<span class="badge bg-danger">' . __("admin::home.Payment failed") . '</span>';

                    }
                    
                })
                ->searchable(),

                // Column::make(__('admin::home.status'), 'status')
                    // ->format(function ($value, $row, Column $column) {
                    //     $status = $row->status;  // Store the status for clarity

                    //     return '<div class="flex items-center">
                    //                 <span class="badge ' . $status->color() . '">' . $status->trans() . '</span>
                    //                 <button wire:click="$emit(\'handleStatusClick\', ' . $row->id . ')">
                    //                     <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    //                 </button>
                    //             </div>';
                    // })
                    // ->searchable(),


            // Column::make(__('admin::home.status'), 'status')
                // ->label(function ($row, Column $column) {
                //         return view('admin::livewire.order.table.historyaction')->withRow($row);
                //         // return view('admin::livewire.order.table.historyaction',['row' => $row?->status]);
                //     }),
                // ->sortable()
                // ->format(function ($value, $row, Column $column) {
                //     echo '<div > <span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span> </div>';
                // }),
                // ->searchable(),


            Column::make(__('admin::home.status'), 'status')
                ->format(function ($value, $row, Column $column) {
                    echo '<div style="cursor: pointer;" wire:click="statusClicked(' . $row->id . ')"> <span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span> </div>';
                })
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),
            
            Column::make(__('admin::home.last update'), 'updated_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),   
            Column::make(__('admin::home.Elm status'), 'elm_order_id')
                    ->hideIf(true),

            Column::make(__('admin::home.Elm status'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.order.table.elmaction',['row' => $row?->elmOrder]);
                })
                ->sortable()
                ->searchable(), 
            Column::make(__('admin::home.Admin Note'), 'admin_note')
            ->format(function ($value, $row, Column $column) {
                echo $value;
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
