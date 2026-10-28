<?php

namespace Application\Admin\Livewire\Wallet\Table\Users\History;


use App\Domain\User\Repositories\UserRepository;
use Bavix\Wallet\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected UserRepository $userRepository;

    use LivewireAlert;
    public $status;
    public $user_id;
    public $filters = [];
    protected $listeners = ['updateStatus', 'refreshCustomers'];
    public function mount(int $user_id,)
    {
        $this->user_id = $user_id;
    }
    

    public function refreshCustomers(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }


    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Transaction::where(['payable_id' => $this->user_id , 'payable_type' => "App\Domain\User\Models\User"])->has('wallet');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Transaction'), 'type')
            ->format(function ($value, $row, Column $column) { 
                    echo $row->type == "withdraw" ? __('admin::home.Withdraw'): __('admin::home.Deposit');
                })
                ->sortable()
                ->searchable(),

            Column::make('wallet_id', 'wallet_id')
                ->hideIf(true),

            Column::make(__('admin::home.Amount'), 'amount')
                ->format(function ($value, $row, Column $column) {
                    echo number_format($row->amountfloat, 2). ' SAR';
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Wallet Type'), 'meta')
                ->format(function ($value, $row, Column $column) { 
                    if(isset($value['type'])){
                        echo  $value['type'] == 'MerchantCoupon' ? $value['source']['merchant_name'] : __('admin::home.Main Wallet');
                    }else{
                        echo str_starts_with($row->wallet->name , 'UID:'.$row->payable_id) ? __('admin::home.Main Wallet') : __('admin::home.Custom Wallet') ;
                    }
                })
                ->sortable()
                ->searchable(),
                

            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'confirmed')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->confirmed == 1) {
                        echo '<span class="badge bg-success">' . __('admin::home.Success') . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __('admin::home.Failed') . '</span>';
                    }
                })
                ->searchable(),
            // Column::make(__('admin::home.actions'), 'action')
            //     ->label(function ($row, Column $column) {
            //         return view('admin::livewire.wallet.table.users.history.action')->withRow($row);
            //     })
      
        ];
    }
}
