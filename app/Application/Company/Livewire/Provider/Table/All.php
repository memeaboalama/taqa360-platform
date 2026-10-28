<?php

namespace Application\Company\Livewire\Provider\Table;


use Carbon\Carbon;
use App\Domain\Provider\Models\Provider;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Provider::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return Provider::where('company_id', auth('company')->user()->id)->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Provider Name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Email Address'), 'email')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Dial Code'), 'dialcode')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Phone Number'), 'phone')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.STATUS'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("company::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("company::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.DATE'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j Y F');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('company::livewire.provider.table.action')->withRow($row);
                })
        ];
    }
}
