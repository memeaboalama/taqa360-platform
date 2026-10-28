<?php

namespace Application\Admin\Livewire\Finance\Reports\Driver\Invoice\Components;

use Domain\MarketPlace\Models\Merchant;
use Livewire\Component;

class SearchMerchant extends Component
{
    public $query;
    public $results;
    public $highlightIndex;

    protected $listeners = ['resetMerchantSearch', 'selectMerchant'];

    public function mount()
    {
        $this->resetMerchantSearch();
    }

    public function resetMerchantSearch()
    {
        $this->query = '';
        $this->results = collect();
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->results) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->results) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function selectMerchant($merchant_id = null)
    {
        $merchant = $this->results->where('id', $merchant_id)->first() ?? null;
        $this->query = $merchant->company_name;
        $this->highlightIndex = $this->results->search(function($result) use($merchant) {
            return $result->id === $merchant->id;
        });
        if ($merchant) {
            $this->emit('refreshMerchantInvoiceFilter', $merchant->id, $merchant->company_name);
        }
    }

    public function updatedQuery()
    {
        $this->results = Merchant::select(['id', 'company_name'])
            ->where('company_name', 'like', '%' . $this->query . '%')
            ->limit(20)
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.finance.reports.driver.invoice.components.search-merchant');
    }
}
