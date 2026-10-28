<?php

namespace Application\Admin\Livewire\Wallet\Table\Users;

use App\Domain\User\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;
use App\Domain\User\Repositories\UserRepository;

class All extends DataTableComponent
{
    use TableConfiguration;

    use LivewireAlert;

    protected UserRepository $userRepository;
    public $status;
    public $filters = [];
    protected $listeners = ['updateStatus', 'refreshCustomers'];

    public function __construct()
    {
        $this->userRepository = new UserRepository(new User());
        parent::__construct();
    }

    public function refreshCustomers(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }


    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->userRepository
            ->filter($this->filters)
            ->select(['id', 'name', 'email', 'phone', 'dialcode', 'status', 'created_at'])
            ->whereHas('wallets', function ($query) {
            $query->whereNotNull('balance');
        });
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                // ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.phone'), 'phone')
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Wallet Balance'), 'balance')
                ->label(function ($row, Column $column) {
                       $wallet = $row->getWallet('UID_'.$row->id.'_Main');
                    echo number_format($wallet?->balanceFloatNum,2).' SAR';
                    
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Merchants Balance'), 'balance')
                ->label(function ($row, Column $column) {
                    $wallets = User::find($row->id)->wallets()
                        ->where('name', '!=', 'UID:'.$row->id.'Main')
                        ->where('description', 'User Merchant Wallet')
                        ->get();
            
                    $totalBalance = 0;
                    foreach ($wallets as $wallet) {
                        $totalBalance += $wallet->balanceFloatNum; 
                    }
            
                    return number_format($totalBalance, 2) . ' SAR';
                })
                ->sortable()
                ->searchable(),
            
            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.wallet.table.users.action')->withRow($row);
                })
      
        ];
    }
}
