<?php

namespace Application\Admin\Livewire\Merchant\Table;

use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchNationalityAction;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Carbon\Carbon;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public $filters = [];
    protected $model = Merchant::class;
    protected MerchantRepository $merchantRepository;

    public $listeners = ['refreshMerchants'];

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


    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantRepository->filter($this->filters, ['id', 'name', 'company_name', 'email', 'phone', 'dialcode', 'status', 'created_at']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);;
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


            Column::make(__('admin::home.email'), 'email')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.email_verified'), 'email_verified_at')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value != null) {
                        echo '<span class="badge bg-success">'.__('admin::home.verified').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.unverified').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.phone'), 'phone')
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.Region'), 'region_id')
                ->format(function ($value, $row, Column $column) {
                    echo $row->region?->title;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.City'), 'city_id')
                ->format(function ($value, $row, Column $column) {
                    echo $row->city?->title;
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

            Column::make(__('admin::home.files'), 'id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->merchantCompleteFiles()) {
                        echo '<span class="badge bg-success">'.__('admin::home.completed').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.Incomplete').'</span>';
                    }
                })
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.merchant.table.action')->withRow($row);
                })
        ];
    }
}
