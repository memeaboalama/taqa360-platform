<?php

namespace Application\Admin\Livewire\Company\Table;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Merchant;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Company::class;
    protected CompanyRepository $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository(new Company());
        parent::__construct();
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->companyRepository
            ->getAllCompaniesAsBuilder(['id', 'name', 'company_name', 'email', 'phone', 'dialcode', 'status', 'created_at'], []);
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

            Column::make(__('admin::home.Company Name'), 'company_name')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.phone'), 'phone')
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.email'), 'email')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value?->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value?->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value?->value") . '</span>';
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
                    return view('admin::livewire.company.table.action')->withRow($row);
                })
        ];
    }
}
