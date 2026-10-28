<?php

namespace Application\Admin\Livewire\AdBanner\Table;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected AdBannerRepository $bannerRepository;
    public int $placementId;

    public function mount(int $placementId)
    {
        $this->placementId = $placementId;
        $this->bannerRepository = new AdBannerRepository(new AdBanner());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(true);
    }

    public function builder(): Builder
    {
        return $this->bannerRepository->builder()
            ->where('placement_id', $this->placementId)
            ->orderByDesc('priority')
            ->orderBy('id');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable(),

            Column::make(__('admin::lang.Banner Name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Title (English)'), 'title')
                ->format(fn($value, $row) => $row->getTranslation('title', app()->getLocale()) ?: '-'),

            Column::make(__('admin::home.Priority'), 'priority')
                ->sortable(),

            Column::make(__('admin::lang.Start Date'), 'start_date')
                ->format(fn($value) => $value ? $value->format('Y-m-d') : '-'),

            Column::make(__('admin::lang.End Date'), 'end_date')
                ->format(fn($value) => $value ? $value->format('Y-m-d') : '-'),

            Column::make(__('admin::lang.Views'), 'views_count')
                ->sortable(),

            Column::make(__('admin::lang.Clicks'), 'clicks_count')
                ->sortable(),

            Column::make(__('admin::lang.CTR'), 'id')
                ->format(fn($value, $row) => $row->ctr . '%'),

            Column::make(__('admin::home.Status'), 'status')
                ->format(fn($value) => view('admin::livewire.ad-banner.table.status', ['status' => $value])),

            Column::make(__('admin::home.Actions'))
                ->label(fn($row) => view('admin::livewire.ad-banner.table.action', ['row' => $row])),
        ];
    }

    #[On('banner-created')]
    #[On('banner-updated')]
    #[On('banner-deleted')]
    public function refresh()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
