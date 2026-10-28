<?php

namespace Application\Admin\Livewire\Banner\Table;

use App\Domain\AppBase\Models\Banner;
use App\Domain\AppBase\Repositories\BannerRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected BannerRepository $bannerRepository;
    public function __construct()
    {
        $this->bannerRepository = new BannerRepository(new Banner());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->bannerRepository->builder(order: 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Image'), 'image')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<img class="img-fluid rounded-start" alt="image" width="100" height="100" src="' . $row->image_path . '" >';
                })
                ->searchable(),


            Column::make(__('admin::lang.Status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::lang.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.banner.table.action')->withRow($row);
                })
        ];
    }
}
