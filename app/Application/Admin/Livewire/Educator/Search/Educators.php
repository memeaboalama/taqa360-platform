<?php

namespace Application\Admin\Livewire\Educator\Search;

use Domain\Educator\Models\Educator;
use Domain\Educator\Repositories\EducatorRepository;
use Livewire\Component;

class Educators extends Component
{
    public $query;
    public $results;
    public $highlightIndex;
    protected EducatorRepository $educatorRepository;

    protected $listeners = ['resetSearch', 'selectEducator'];

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

    public function selectEducator($educator_id = null)
    {
        $educator = $this->results->where('id', $educator_id)->first() ?? null;
        if (!$educator) {
            return;
        }
        $this->query = $educator->name . "(" . $educator->phone . ")";
        $this->highlightIndex = $this->results->search(function($result) use($educator) {
            return $result->id === $educator->id;
        });
        $this->emit('refreshEducatorFilter', $educator->id);
    }

    public function updatedQuery()
    {
        $this->educatorRepository = new EducatorRepository(new Educator());
        $this->results = $this->educatorRepository->search(['id', 'name', 'phone'],$this->query);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.educator.search.educators');
    }
}
