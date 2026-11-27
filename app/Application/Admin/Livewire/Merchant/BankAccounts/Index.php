<?php

namespace Application\Admin\Livewire\Merchant\BankAccounts;

use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $status = '';
    public string $date_from = '';
    public string $date_to = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'date_from', 'date_to']);
        $this->resetPage();
    }

    public function render()
    {
        $query = MerchantBankAccountRequest::with('merchant')
            ->orderByRaw("CASE WHEN status = 'pending_review' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('request_number', 'like', $term)
                    ->orWhere('bank_name', 'like', $term)
                    ->orWhere('iban', 'like', $term)
                    ->orWhereHas('merchant', function ($mq) use ($term) {
                        $mq->where('company_name', 'like', $term)
                            ->orWhere('name', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    });
            });
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->date_from !== '') {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to !== '') {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        $requests = $query->paginate(15);

        return view('admin::livewire.merchant.bank-accounts.index', compact('requests'));
    }
}
