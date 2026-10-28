<?php

namespace Application\Admin\Livewire\Promocode\Table;

use App\Application\Marketplace\Enums\PromocodeStatuses;
use App\Application\Marketplace\Enums\PromocodeTypes;

use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $code = null;
    public $status = null;
    public $type = null;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;

    public Collection $statuses;
    public Collection $types;
    public array $filters = [];

    protected $listeners = [
        'removeCodeFilter',
        'removeStatusFilter',
        'removeTypeFilter',
        'removeDateFilter',
        'getBetweenDates'];

    public function mount()
    {
        $this->date_condition = "equal";
        $this->statuses = collect(PromocodeStatuses::cases());
        $this->types = collect(PromocodeTypes::cases());
    }

    public function resetSearch(): void
    {
        $this->emit('resetSearch');
    }

    public function filterCode(): void
    {
        $this->filters['code'] =  $this->code;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function removeCodeFilter(): void
    {
        $this->code = null;
        $this->filters['code'] = null;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function filterStatus(): void
    {
        $this->statuses = collect(PromocodeStatuses::cases());
        $this->filters['status'] =  $this->status;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(PromocodeStatuses::cases());
        $this->filters['status'] = null;
        $this->emit('refreshPromocode', $this->filters);
    }
    public function filterType(): void
    {
        $this->types = collect(PromocodeTypes::cases());
        $this->filters['type'] =  $this->type;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function removeTypeFilter(): void
    {
        $this->type = null;
        $this->types = collect(PromocodeTypes::cases());
        $this->filters['type'] = null;
        $this->emit('refreshPromocode', $this->filters);
    }


    public function filterDate(): void
    {
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->date_condition = "equal";
        $this->date = null;
        $this->to_date = null;
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshPromocode', $this->filters);
    }

    public function getBetweenDates(): void
    {
        if ($this->date_condition == "between")
        {
            $this->to_date_visible = true;
        }else{
            $this->to_date_visible = false;
        }
    }

    public function render()
    {
        return view('admin::livewire.promocode.table.filter');
    }
}
