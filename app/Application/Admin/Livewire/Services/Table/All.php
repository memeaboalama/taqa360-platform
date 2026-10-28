<?php

namespace Application\Admin\Livewire\Services\Table;

use App\Domain\Provider\Models\ProviderMainService;
use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use Carbon\Carbon;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Admin::class;
    protected ProviderMainServiceRepository $providerMainServiceRepository;

    public function __construct()
    {
        $this->providerMainServiceRepository = new ProviderMainServiceRepository(new ProviderMainService());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->providerMainServiceRepository->builder();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make('title', 'title')
                ->sortable()
                ->searchable(),


            Column::make('starter price', 'starter_price')
                ->sortable()
                ->searchable(),

            Column::make('estimated time', 'estimated_time')
                ->sortable()
                ->searchable(),


            Column::make('main category', 'main_category_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return $row->main_category?->title;
                })
                ->searchable(),

            Column::make('Provider Category', 'provider_category_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return $row->provider_category?->title;
                })
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $value?->color() . '">' . $value?->trans() . '</span>';
                })
                ->searchable(),

            Column::make('Date', 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make('actions', 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.services.table.action')->withRow($row);
                })
        ];
    }
}
