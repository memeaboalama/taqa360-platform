<?php
namespace Application\Marketplace\Livewire\Product\Search;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Collection;
use Livewire\Component;

class Search extends Component
{
    public $searchType = 'item';
    public $query;
    public $results;
    public $highlightIndex;
    protected ItemRepository $itemRepository;

    protected $listeners = ['resetSearch', 'selectItem'];

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

    public function selectItem($item_id = null)
    {
        $result = $this->results->where('id', $item_id)->first() ?? null;
        if ($result) {
            $label = $result->name ?? $result->title;
            $this->query = $label;
            $this->highlightIndex = $this->results->search(function ($item) use ($item_id) {
                return $item->id === (int) $item_id;
            });

            if ($this->searchType === 'merchant') {
                $this->emit('refreshProductsFilter', 'merchant', $result->id, $label);
            } else {
                $this->emit('refreshProductsFilter', 'item', $result->id, $label);
            }
        }
    }

    public function updatedQuery()
    {
        $term = trim($this->query);

        if ($term === '') {
            $this->results = collect();
            return;
        }

        if ($this->searchType === 'merchant') {
            $this->results = Merchant::query()
                ->where('status', 'active')
                ->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', '%' . $term . '%')
                        ->orWhere('company_name', 'like', '%' . $term . '%');
                })
                ->limit(20)
                ->get(['id', 'name', 'company_name']);
            return;
        }

        $this->itemRepository = new ItemRepository(new Item());
        $this->results = $this->itemRepository->search(['id', 'title', 'default_price'], $term);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.product.search.search');
    }
}
