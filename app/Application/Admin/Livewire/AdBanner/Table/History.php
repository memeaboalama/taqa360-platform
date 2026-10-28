<?php

namespace Application\Admin\Livewire\AdBanner\Table;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class History extends DataTableComponent
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
            ->where('status', '!=', 'active')
            ->orderByDesc('updated_at');
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
                ->format(fn($value) => $value['en'] ?? '-'),

            Column::make(__('admin::lang.Status'), 'status')
                ->format(fn($value) => view('admin::livewire.ad-banner.table.status', ['status' => $value]))
                ->html(),

            Column::make(__('admin::lang.Last Updated'), 'updated_at')
                ->format(fn($value) => $value->diffForHumans()),

            Column::make(__('admin::lang.Actions'), 'id')
                ->format(function($value, $row) {
                    return view('admin::livewire.ad-banner.table.history-actions', ['banner' => $row]);
                })
                ->html(),
        ];
    }

    #[On('banner-reactivated')]
    public function refresh()
    {
        $this->emit('refreshLivewireDatatable');
    }
}
