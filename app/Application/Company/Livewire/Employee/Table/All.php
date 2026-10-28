<?php

namespace Application\Company\Livewire\Employee\Table;

use App\Domain\Provider\Models\CompanyEmployee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{

    use TableConfiguration;
    public $companyId;

    protected $model = CompanyEmployee::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function mountComponentUtilities()
    {
        $this->companyId = resolve('_InitialCompany');
    }

    public function builder(): Builder
    {
        return CompanyEmployee::where('company_id', $this->companyId)->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.Email Address'), 'email')
                ->sortable()
                ->searchable(),


            Column::make(__('company::home.Phone Number'), 'phone')
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.ROLE NAME'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    $role_name = (strstr($row?->roles()?->first()?->name , '_', true)) ? strstr($row?->roles()?->first()?->name , '_', true) : $row?->roles()?->first()?->name;
                    echo '<span class="badge bg-success">'. $role_name .'</span>';
                })
                ->searchable(),


            Column::make(__('company::home.STATUS'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("company::home.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("company::home.$value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('company::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('company::livewire.employee.table.action')->withRow($row);
                })
        ];
    }

}
