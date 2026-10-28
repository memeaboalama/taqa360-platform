<?php

namespace Application\Admin\Livewire\AdPage\Table;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPageRepository;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected AdPageRepository $pageRepository;

    public function __construct()
    {
        $this->pageRepository = new AdPageRepository(new AdPage());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->pageRepository->builder()->withCount('placements');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable(),

            Column::make(__('admin::lang.Name'), 'name')
                ->format(fn($value, $row) => $row->getTranslation('name', app()->getLocale()) ?: $row->getTranslation('name', 'en'))
                ->sortable(),

            Column::make(__('admin::lang.Key'), 'key')
                ->sortable(),

            Column::make(__('admin::lang.Ad Placements'), 'id')
                ->format(fn($value, $row) => $row->placements_count . ' ' . __('admin::lang.Ad Placements')),

            Column::make(__('admin::home.Status'), 'status')
                ->format(fn($value) => view('admin::livewire.ad-page.table.status', ['status' => $value])),

            Column::make(__('admin::home.Actions'))
                ->label(fn($row) => view('admin::livewire.ad-page.table.action', ['row' => $row])),
        ];
    }
}
