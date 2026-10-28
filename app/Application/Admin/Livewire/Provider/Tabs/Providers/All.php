<?php

namespace Application\Admin\Livewire\Provider\Tabs\Providers;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use App\Domain\Provider\Models\Provider;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantService;
use Domain\Provider\Repositories\ProviderRepository;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public int $company_id;
    protected $model = Provider::class;
    protected ProviderRepository $providerRepository;

    public function __construct()
    {
        $this->providerRepository = new ProviderRepository(new Provider());
        parent::__construct();
    }

    public function mount($company_id): void
    {
        $this->company_id = $company_id;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->providerRepository->getByCompanyId($this->company_id);
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

            Column::make('name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('email', 'email')
                ->sortable()
                ->searchable(),


            Column::make('phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('country', 'country_id')
                ->format(function ($value, $row, Column $column) {
                    return $row->country->title;
                })
                ->sortable()
                ->searchable(),


            Column::make('status', 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value?->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value?->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value?->value") . '</span>';
                    }
                })
                ->searchable(),


            Column::make('Date', 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),


        ];
    }
}
