<?php

namespace Application\Admin\Livewire\Role\Table;

use App\Support\Repositories\Spatie\RoleRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $merchantId;
    protected RoleRepository $roleRepository;

    public function __construct()
    {
        $this->roleRepository = new RoleRepository(new Role());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->roleRepository->getAsBuilder(cols:['id', 'name'], filters:['guard_name' => 'admin', 'team_id' => 1]);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Role Name'), 'name')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo (strstr($value, '_', true)) ? strstr($value, '_', true) : $value;
                })
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.role.table.action')->withRow($row);
                })
        ];
    }
}
