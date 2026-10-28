<?php

namespace Application\Admin\Livewire\Notification\Table;

use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\AppBase\Repositories\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected NotificationRepository $notificationRepository;

    public function __construct()
    {
        $this->notificationRepository = new NotificationRepository(new NotificationNew());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);;
    }

    public function builder(): Builder
    {
        return $this->notificationRepository->builder(order: 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.message'), 'body')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Send Name'), 'id')
            ->format(function ($value, $row, Column $column) {
                echo $row?->admin?->name;
            })
                ->sortable()
                ->searchable(),



            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.notification.table.action')->withRow($row);
                })
        ];
    }
}
