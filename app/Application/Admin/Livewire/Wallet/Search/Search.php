<?php

namespace Application\Admin\Livewire\Wallet\Search;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use Domain\SalesAgent\Models\SalesAgent;
use Livewire\Component;

class Search extends Component
{
    public $query;
    public $results;
    public $highlightIndex;

    public $disblyList = "";

    protected SalesAgentRepository $salesAgentRepository;

    protected $listeners = ['resetSearch', 'setSalesAgent'];

    public function mount()
    {
        $this->resetSearch();
    }

    public function setSalesAgent($salesAgent = null)
    {
        if (!$salesAgent) {
            $this->resetSearch();
            return;
        }

        if (is_numeric($salesAgent)) {
            $salesAgent = SalesAgent::find($salesAgent);
        }

        if (is_array($salesAgent)) {
            $name = $salesAgent['name'] ?? '';
            $phone = $salesAgent['phone'] ?? '';
            $this->query = $name . ($phone ? " (" . $phone . ")" : "");
        } elseif (is_object($salesAgent)) {
            $this->query = $salesAgent->name . ($salesAgent->phone ? " (" . $salesAgent->phone . ")" : "");
        }
        $this->disblyList = "none";
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

    public function selectSalesAgent($sales_agent_id = null)
    {
        $salesAgent = $this->results->where('id', $sales_agent_id)->first() ?? null;
        $this->query = $salesAgent->name . "(" . $salesAgent->phone . ")";
        $this->highlightIndex = $this->results->search(function($result) use($salesAgent) {
            return $result->id === $salesAgent->id;
        });
        if ($salesAgent) {
            $this->emitUp('selectSalesAgent', $salesAgent);
        }
        $this->disblyList = "none";
        
    }

    public function updatedQuery()
    {
        $this->disblyList = "";
        $this->salesAgentRepository = new SalesAgentRepository(new SalesAgent());
        $this->results = $this->salesAgentRepository->search(['id', 'name', 'phone'],$this->query);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.wallet.search.search');
    }
}
