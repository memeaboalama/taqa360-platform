<?php

namespace Application\Admin\Livewire\Educator\Table;

use App\Application\Educator\Enums\EducatorStatus;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $educator_id = null;
    public $educator_name = null;
    public $educator_phone = null;
    public Collection $statuses;
    public array $filters = [];

    protected $listeners = [
        'refreshEducatorFilter',
        'removeEducatorFilter',
        'removeStatusFilter',
    ];

    public function mount()
    {
        $this->statuses = $this->statusValues();
    }

    public function refreshEducatorFilter($educator_id = null): void
    {
        $educator = Educator::find($educator_id);
        if (!$educator) {
            return;
        }
        $this->educator_id = $educator->id;
        $this->educator_name = $educator->name ?? null;
        $this->educator_phone = $educator->phone ?? null;
        $this->filters['educator_id'] =  $this->educator_id;
        $this->emit('refreshEducators', $this->filters);
    }

    public function removeEducatorFilter(): void
    {
        $this->educator_id = null;
        $this->educator_name = null;
        $this->educator_phone = null;
        $this->filters['educator_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshEducators', $this->filters);
    }

    public function filterStatus(): void
    {
        $this->statuses = $this->statusValues();
        $this->filters['status'] =  $this->status;
        $this->emit('refreshEducators', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = $this->statusValues();
        $this->filters['status'] = null;
        $this->emit('refreshEducators', $this->filters);
    }

    private function statusValues(): Collection
    {
        $allowed = ['active', 'pending', 'rejected'];

        return collect(array_map(
            fn($status) => $status->value,
            array_filter(
                EducatorStatus::cases(),
                fn($status) => in_array($status->value, $allowed, true)
            )
        ));
    }

    public function render()
    {
        return view('admin::livewire.educator.table.filter');
    }
}
