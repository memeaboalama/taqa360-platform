<?php

namespace Application\Marketplace\Livewire\Branch\Table;

use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchAction;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{

    use TableConfiguration;
    use LivewireAlert;

    public $merchant, $emp;
    protected $model = MerchantBranch::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function mountComponentUtilities()
    {
        $this->merchant = auth()->guard('merchant')?->user();
        $this->emp = auth()->guard('merchant_employee')?->user();
    }

    public function builder(): Builder
    {
        if ($this->emp)
        {
            return MerchantBranch::where('merchant_id', $this->emp->merchant_id)
                ->whereHas('employees', function ($q){
                    $q->where('merchant_employee_id', $this->emp->id);
                })
                ->orWhereHas('manager', function ($q){
                    $q->where('id', $this->emp->id);
                })
                ->latest();
        }
        return MerchantBranch::where('merchant_id', $this->merchant->id)->latest();
    }

    public function updateStatus(UpdateBranchAction $updateBranchAction, $status, $branch_id)
    {
        DB::beginTransaction();
        $updateBranchAction->handle($branch_id, ['status' => $status]);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        $this->emit('refreshDatatable');
        DB::commit();
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


            Column::make(__('marketplace::home.City'), 'city_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->city?->title;
                })
                ->searchable(),


            Column::make(__('marketplace::home.status'), 'status')
                ->sortable()

                ->format(function ($value,$row, Column $column) {
                    return view('marketplace::livewire.branch.components.status_all')->withRow($row);
                }),

            Column::make(__('marketplace::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.branch.table.action')->withRow($row);
                })
        ];
    }

}
