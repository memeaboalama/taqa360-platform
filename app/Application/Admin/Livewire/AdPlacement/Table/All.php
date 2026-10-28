<?php

namespace Application\Admin\Livewire\AdPlacement\Table;

use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected AdPlacementRepository $placementRepository;
    public int $pageId;

    public function mount(int $pageId)
    {
        $this->pageId = $pageId;
        $this->placementRepository = new AdPlacementRepository(new AdPlacement());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->placementRepository->builder()
            ->where('page_id', $this->pageId)
            ->withCount('banners');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable(),

            Column::make(__('admin::lang.Placement Name'), 'name')
                ->format(fn($value, $row) => $row->getTranslation('name', app()->getLocale()) ?: $row->getTranslation('name', 'en'))
                ->sortable(),

            Column::make(__('admin::lang.Placement Type'), 'type')
                ->format(fn($value) => $value->label()),

            Column::make(__('admin::lang.Location'), 'location')
                ->sortable(),

            Column::make(__('admin::lang.Ad Banners'), 'id')
                ->format(fn($value, $row) => $row->banners_count . ' ' . __('admin::lang.Ad Banners')),

            Column::make(__('admin::home.Status'), 'status')
                ->format(fn($value) => view('admin::livewire.ad-placement.table.status', ['status' => $value])),

            Column::make(__('admin::home.Actions'))
                ->label(fn($row) => view('admin::livewire.ad-placement.table.action', ['row' => $row])),
        ];
    }

    #[On('placement-created')]
    #[On('placement-updated')]
    #[On('placement-deleted')]
    public function refresh()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
