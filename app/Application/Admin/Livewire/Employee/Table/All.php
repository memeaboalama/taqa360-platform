<?php

namespace Application\Admin\Livewire\Employee\Table;

use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchCategoryAction;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Carbon\Carbon;
use Domain\Admin\Models\Admin;
use Domain\Admin\Repositories\AdminRepository;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Admin::class;
    protected AdminRepository $adminRepo;

    public function __construct()
    {
        $this->adminRepo = new AdminRepository(new Admin());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setSearchStatus(false)
             ->setDefaultSort('id', 'desc');
    }

    public function builder(): Builder
    {
        return $this->adminRepo->getEmployees();
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

            Column::make(__('admin::home.Role Name'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    $role_name = (strstr($row?->roles()?->first()?->name , '_', true)) ? strstr($row?->roles()?->first()?->name , '_', true) : $row?->roles()?->first()?->name;
                    echo '<span class="badge bg-success">'. $role_name .'</span>';
                })
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("admin::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("admin::lang.$value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.employee.table.action')->withRow($row);
                })
        ];
    }
}
