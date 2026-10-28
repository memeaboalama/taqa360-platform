<?php

namespace Application\Marketplace\Livewire\Choice\Table;

use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchProviderAction;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Choice;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $merchantId;

    protected $model = Choice::class;

    public int $perPage = 10;

    public array $perPageAccepted = [10, 25, 50, 100];

    public function __construct()
    {
        parent::__construct();
    }

    public function mountComponentUtilities()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
        $this->setColumnSelectEnabled();
    }

    public function builder(): Builder
    {
        return Choice::where('merchant_id', $this->merchantId)->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.Title'), 'title')
                ->sortable()
                ->searchable(),


            Column::make(__('marketplace::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('marketplace::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.choice.table.action')->withRow($row);
                })
        ];
    }
}
