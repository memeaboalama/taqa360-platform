<?php

namespace Application\Admin\Livewire\Package\Table;

use App\Domain\MarketPlace\Models\Package;
use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected PackageRepository $packageRepository;
    public function __construct()
    {
        $this->packageRepository = new PackageRepository(new Package());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->packageRepository->builder()
            ->with('type_services')
            ->reorder()
            ->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Commission (%)'), 'commission')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    $commissions = $row->type_services
                        ->map(fn($ts) => $ts->pivot->commission)
                        ->filter(fn($c) => $c > 0);
                    if ($commissions->isEmpty()) {
                        echo 0;
                    } else {
                        echo $commissions->map(function ($c) {
                            $formatted = rtrim(rtrim(number_format((float)$c, 2, '.', ''), '0'), '.');
                            return '<span class="badge bg-primary-transparent text-primary border-0 px-2 py-1">' . $formatted . '%</span>';
                        })->implode(' ');
                    }
                }),


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
                    return view('admin::livewire.package.table.action')->withRow($row);
                })
        ];
    }
}
