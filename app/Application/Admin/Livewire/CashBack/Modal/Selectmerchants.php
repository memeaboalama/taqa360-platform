<?php

namespace Application\Admin\Livewire\CashBack\Modal;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\AppBase\Models\CashBackMerchantsChoice;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchNationalityAction;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Carbon\Carbon;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class Selectmerchants extends DataTableComponent
{
    public function mount(int $cashback_id,)
    {
        $this->cashback_id = $cashback_id;
    }

    public $listeners = ['updateStatus' ,'refreshMerchants'];


    use TableConfiguration;

    public $filters = [];
    protected $model = Merchant::class;
    protected MerchantRepository $merchantRepository;


    public function __construct()
    {
        $this->merchantRepository = new MerchantRepository(new Merchant());
        parent::__construct();
    }

    public function refreshMerchants(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }
    public function updateStatus($status, $merchant_id)
    {
       $status ? $this->addMerchant($merchant_id) : $this->deleteMerchant($merchant_id);
       //$this->emit('refreshDatatable');
    }
    public function addMerchant($merchant_id)
    {
        CashBackMerchantsChoice::create(['cashback_id' => $this->cashback_id ,'merchant_id' => $merchant_id]);
    }
    public function deleteMerchant($merchant_id)
    {
        CashBackMerchantsChoice::where(['cashback_id' => $this->cashback_id ,'merchant_id' => $merchant_id])->delete();
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantRepository->filter($this->filters, ['id', 'company_name', 'email', 'phone', 'status']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.name'), 'company_name')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.phone'), 'phone')
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.City'), 'city_id')
                ->format(function ($value, $row, Column $column) {
                    echo $row->city?->title;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.District'), 'distract_id')
                ->format(function ($value, $row, Column $column) {
                    echo $row->district?->title;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value") . '</span>';
                    }
                })
                ->searchable(),

                Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    
                    return view('admin::livewire.cashback.components.selectmerchant', [
                        'row' => $row,
                        'cash_status' => CashBackMerchantsChoice::where(['cashback_id' => $this->cashback_id,'merchant_id'=> $row->id])->exists() ? 1 : 0,
                    ]);
                }), 
            
        ];
    }

}