<?php

namespace Application\Admin\Livewire\User\Table;

use App\Domain\Admin\Actions\Dashboard\User\UpdateBasicInfoAction;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected UserRepository $userRepository;

    use LivewireAlert;
    public $status;
    public $filters = [];
    protected $listeners = ['updateStatus', 'refreshCustomers'];

    public function __construct()
    {
        $this->userRepository = new UserRepository(new User());
        parent::__construct();
    }

    public function refreshCustomers(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->userRepository
            ->filter($this->filters)
            ->select(['id', 'name', 'email', 'phone', 'dialcode', 'status', 'created_at']);
    }


    public function updateStatus(UpdateBasicInfoAction $updateBasicInfoAction ,$status, $id): void
    {
        DB::beginTransaction();
        $updateBasicInfoAction->handle($id, ['status' => $status]);
        $this->alert('success', "Customer Updated Successfully");
        $this->emit('refreshDatatable');
        DB::commit();
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
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')

                ->label(function ($row, Column $column) {
                    return view('admin::livewire.user.table.block')->withRow($row);
                }),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.user.table.action')->withRow($row);
                })
        ];
    }
}
