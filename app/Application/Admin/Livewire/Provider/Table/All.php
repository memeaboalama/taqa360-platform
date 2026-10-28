<?php

namespace Application\Admin\Livewire\Provider\Table;

use App\Domain\Provider\Models\Provider;
use Carbon\Carbon;

use Domain\Provider\Repositories\ProviderRepository;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Provider::class;
    protected ProviderRepository $providerRepository;

    public function __construct()
    {
        $this->providerRepository = new ProviderRepository(new Provider());
        parent::__construct();
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->providerRepository->getAllProvidersAsBuilder();
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

            Column::make(__('admin::home.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.email'), 'email')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.phone'), 'phone')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Country'), 'country_id')
                ->format(function ($value, $row, Column $column) {
                    return $row->country->title;
                })
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
                    return view('admin::livewire.provider.table.action')->withRow($row);
                })


        ];
    }
}
