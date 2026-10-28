<?php

namespace Application\Admin\Livewire\User\Table;

use App\Domain\User\Models\User;
use App\Support\Enum\ActiveEnum;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $customer_id = null;
    public $customer_name = null;
    public $customer_phone = null;
    public Collection $statuses;
    public array $filters = [];

    protected $listeners = [
        'refreshCustomerFilter',
        'removeCustomerFilter',
        'removeStatusFilter',
    ];

    public function mount()
    {
        $this->statuses = collect(ActiveEnum::cases());
    }

    public function refreshCustomerFilter(User $customer): void
    {
        $this->statuses = collect(ActiveEnum::cases());
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->name ?? null;
        $this->customer_phone = $customer->phone ?? null;
        $this->filters['customer_id'] =  $this->customer_id;
        $this->emit('refreshCustomers', $this->filters);
    }

    public function removeCustomerFilter(): void
    {
        $this->statuses = collect(ActiveEnum::cases());
        $this->customer_id = null;
        $this->customer_name = null;
        $this->customer_phone = null;
        $this->filters['customer_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshCustomers', $this->filters);
    }


    public function filterStatus(): void
    {
        $this->statuses = collect(ActiveEnum::cases());
        $this->filters['status'] =  $this->status;
        $this->emit('refreshCustomers', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(ActiveEnum::cases());
        $this->filters['status'] = null;
        $this->emit('refreshCustomers', $this->filters);
    }
    public function render()
    {
        return view('admin::livewire.user.table.filter');
    }
}
