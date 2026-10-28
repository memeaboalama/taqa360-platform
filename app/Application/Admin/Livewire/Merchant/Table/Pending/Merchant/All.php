<?php

namespace Application\Admin\Livewire\Merchant\Table\Pending\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Enum\StatusEnum;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Merchant;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected $model = Merchant::class;
    protected MerchantRepository $merchantRepository;

    public function __construct()
    {
        $this->merchantRepository = new MerchantRepository(new Merchant());
        parent::__construct();
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->merchantRepository
            ->getAllMerchantsAsBuilder(['id', 'name', 'company_name', 'email', 'phone', 'dialcode', 'status', 'created_at'], [])
            ->where('status', StatusEnum::INACTIVE->value)
            ->has('files', '<' , 8);
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

            Column::make(__('admin::home.name'), 'company_name')
                ->sortable()
                ->searchable(),


            Column::make(__('admin::home.email'), 'email')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.email_verified'), 'email_verified_at')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value != null) {
                        echo '<span class="badge bg-success">'.__('admin::home.verified').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.unverified').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.phone'), 'phone')
                ->format(function ($value, $row, Column $column) {
                    echo $row->phone_number;
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.files'), 'id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->merchantCompleteFiles()) {
                        echo '<span class="badge bg-success">'.__('admin::home.completed').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.Incomplete').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.merchantStatus'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if (!$row->merchantCompleteFiles() && $value == 'inactive') {
                        echo '<span class="badge bg-warning">'.__('admin::home.pending').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.merchant.table.action')
                        ->withRow($row)
                        ->with('pending', true);
                })
        ];
    }
}
