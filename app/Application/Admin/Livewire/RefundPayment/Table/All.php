<?php

namespace Application\Admin\Livewire\RefundPayment\Table;

use App\Domain\RefundPayment\Repositories\RefundPaymentRepository;
use Carbon\Carbon;
use App\Domain\AppBase\Models\RefundPayment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected RefundPaymentRepository $refundpaymentRepository;
    public $listeners = ['refreshRefundPayments'];
    public function __construct()
    {
        $this->refundpaymentRepository = new RefundPaymentRepository(new RefundPayment());
        parent::__construct();
    }

    public function refreshRefundPayments(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return $this->refundpaymentRepository->filter($this->filters);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.RefundpaymentId'), 'id')
                ->sortable()
                ->searchable(),



            Column::make(__('admin::home.RefundpaymentType'), 'type')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'toWallet') {
                        echo '<span class="badge bg-success">' . __("admin::home.RefundpaymentType toWallet") . '</span>';
                    } else {
                        echo '<span class="badge bg-info">' . __("admin::home.RefundpaymentType toAccount") . '</span>';
                    }
                    
                })
                ->searchable(),

            Column::make(__('admin::home.RefundpaymentAmount'), 'amount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->amount;
                })
                ->searchable(),

                Column::make(__('admin::home.calculated on'), 'calculated_on')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'company') {
                        echo '<span class="badge bg-success">' . __("admin::home.Company") . '</span>';
                    }
                    else if ($value == 'delivery')  {
                        echo '<span class="badge bg-info">' . __("admin::home.Delivery") . '</span>';
                    }
                     else {
                        echo '<span class="badge bg-primary">' . __("admin::home.Store") . '</span>';
                    }
                    
                })
                ->searchable(),
            Column::make(__('admin::home.RefundpaymentNote'), 'note')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->note;
                })
                ->searchable(),
            Column::make(__('admin::home.Customer Name'), 'user_name')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user_name;
                })
                ->searchable(),
            Column::make(__('admin::home.Order No'), 'order_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->order_id;
                })
                ->searchable(),
            Column::make(__('admin::home.PaymentId'), 'payment_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->payment_id;
                })
                ->searchable(),
                
            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            // Column::make(__('admin::home.actions'), 'action')
            //     ->label(function ($row, Column $column) {
            //         return view('admin::livewire.refundpayment.table.action')->withRow($row);
            //     })

        ];
    }
}
