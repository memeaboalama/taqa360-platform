<?php

namespace Application\Admin\Livewire\Offer\Table;

use App\Application\Marketplace\Enums\OfferStatuses;
use App\Application\Marketplace\Enums\OfferTypes;

use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $name = null;
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
        'removeNameFilter',
        'removeStatusFilter',
        'removeTypeFilter',
        'removeDateFilter',
        'getBetweenDates'];

    public function mount()
    {
        $this->date_condition = "equal";
        $this->statuses = collect(OfferStatuses::cases());
        $this->types = collect(OfferTypes::cases());
    }

    public function resetSearch(): void
    {
        $this->emit('resetSearch');
    }

    public function filterName(): void
    {
        $this->filters['name'] =  $this->name;
        $this->emit('refreshOffer', $this->filters);
    }

    public function removeNameFilter(): void
    {
        $this->name = null;
        $this->filters['name'] = null;
        $this->emit('refreshOffer', $this->filters);
    }

    public function filterStatus(): void
    {
        $this->statuses = collect(OfferStatuses::cases());
        $this->filters['status'] =  $this->status;
        $this->emit('refreshOffer', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(OfferStatuses::cases());
        $this->filters['status'] = null;
        $this->emit('refreshOffer', $this->filters);
    }
    public function filterType(): void
    {
        $this->types = collect(OfferTypes::cases());
        $this->filters['type'] =  $this->type;
        $this->emit('refreshOffer', $this->filters);
    }

    public function removeTypeFilter(): void
    {
        $this->type = null;
        $this->types = collect(OfferTypes::cases());
        $this->filters['type'] = null;
        $this->emit('refreshOffer', $this->filters);
    }


    public function filterDate(): void
    {
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshOffer', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->date_condition = "equal";
        $this->date = null;
        $this->to_date = null;
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshOffer', $this->filters);
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
        return view('admin::livewire.offer.table.filter');
    }
}
