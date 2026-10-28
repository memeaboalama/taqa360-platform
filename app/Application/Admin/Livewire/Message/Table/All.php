<?php

namespace Application\Admin\Livewire\Message\Table;

use App\Domain\Admin\Actions\Dashboard\Message\UpdateStatusAction;
use App\Domain\AppBase\Models\Message;
use App\Domain\AppBase\Repositories\MessageRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected MessageRepository $messageRepository;

    use LivewireAlert;
    public $status;
    protected $listeners = ['updateStatus'];

    public function __construct()
    {
        $this->messageRepository = new MessageRepository(new Message());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->messageRepository->builder();
    }

    public function updateStatus(UpdateStatusAction $updateStatusAction ,$status, $id): void
    {
        DB::beginTransaction();
        $updateStatusAction->handle($id, ['status' => $status]);
        $this->alert('success', __('admin::home.Message Updated Successfully'));
        DB::commit();
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

            Column::make(__('admin::home.message'), 'message')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.contacted'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.message.table.action')->withRow($row);
                })

        ];
    }
}
