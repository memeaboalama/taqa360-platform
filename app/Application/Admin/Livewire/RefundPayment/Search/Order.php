<?php

namespace Application\Admin\Livewire\RefundPayment\Search;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Order extends Component
{
    public $query;
    public $results;
    public $highlightIndex;
    protected MerchantOrderRepository $orderRepository;

    protected $listeners = ['resetSearch', 'selectOrder'];

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

    public function selectOrder($order_id = null)
    {
        $order = $this->results->where('id', $order_id)->first() ?? null;
        $this->query = $order->code . "(" . $order->client_name. ")". "(" . $order->client_phone . ")";
        $this->highlightIndex = $this->results->search(function($result) use($order) {
            return $result->id === $order->id;
        });
        if ($order) {
            $this->emit('refreshOrderData', $order);
        }
    }

    public function updatedQuery()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $this->results = $this->orderRepository->searchOrderComplete(['id','code','client_name','client_phone','user_id'],$this->query);
        // Log::info($this->results);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.refundpayment.search.order');
    }
}
