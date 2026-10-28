<?php

namespace Application\Admin\Livewire\User\Search;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Livewire\Component;

class Users extends Component
{
    public $query;
    public $results;
    public $highlightIndex;
    protected UserRepository $userRepository;

    protected $listeners = ['resetSearch', 'selectCustomer'];

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

    public function selectCustomer($customer_id = null)
    {
        if (!$customer_id)
        return;
        $customer = $this->results->where('id', $customer_id)->first() ?? null;
        if ($customer) {
            $this->query = $customer->name . "(" . $customer->phone . ")";
            $this->highlightIndex = $this->results->search(function ($result) use ($customer) {
                return $result->id === $customer->id;
            });
            if ($customer) {
                $this->emit('refreshCustomerFilter', $customer);
            }
        }
    }

    public function updatedQuery()
    {
        $this->userRepository = new UserRepository(new User());
        $this->results = $this->userRepository->search(['id', 'name', 'phone'], $this->query);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.user.search.users');
    }
}
