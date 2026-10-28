<?php

namespace Application\Admin\Livewire\Order\Search;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Domain\MarketPlace\Models\Merchant;
use Livewire\Component;

class Merchants extends Component
{
    public $query;
    public $results;
    public $highlightIndex;
    protected MerchantRepository $merchantRepository;

    protected $listeners = ['resetSearch', 'selectMerchant'];

    public function mount()
    {
        $this->resetSearch();
    }

    public function resetSearch()
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
            $this->emit('refreshMerchantFilter', $merchant->id, $merchant->company_name);
        }
    }

    public function updatedQuery()
    {
        $this->merchantRepository = new MerchantRepository(new Merchant());
        $this->results = $this->merchantRepository->search(['id', 'company_name'],$this->query);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.search.merchants');
    }
}
