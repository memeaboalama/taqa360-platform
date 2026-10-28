<?php

namespace Application\Marketplace\Livewire\Finance\Reports\Order\Components;

use App\Application\Admin\Exports\Orders\MerchantOrderExport;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class Export extends Component
{
    public array $filters = [];
    public bool $hasRecords = true;
    protected MerchantOrderRepository $orderRepository;
    public $merchantId;
    protected $listeners = ['prepareMerchantOrderExportMark', 'export'];

    protected function getMerchantId()
    {
        if (app()->bound('_InitialMerchant') && app('_InitialMerchant')) {
            return app('_InitialMerchant');
        }
        if (Auth::guard('merchant')->check()) {
            return Auth::guard('merchant')->user()->id;
        }
        if (Auth::guard('merchant_employee')->check()) {
            return Auth::guard('merchant_employee')->user()->merchant_id;
        }
        return null;
    }

    public function prepareMerchantOrderExportMark($filters)
    {
        $this->filters = $filters;
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->checkHasRecords();
    }

    public function mount()
    {
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->checkHasRecords();
    }

    public function checkHasRecords()
    {
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $count = $this->orderRepository->filterOrders($this->filters, [], ['id'])->count();
        $this->hasRecords = $count > 0;
    }

    public function export()
    {
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $ordersQuery = $this->orderRepository->filterOrders($this->filters,
                ['user', 'merchant', 'branch', 'payment', 'merchant.packages'], ['*']);

        if ($ordersQuery->count() === 0) {
            $this->hasRecords = false;
            $this->emit('toastr', [
                'type' => 'warning',
                'message' => __('marketplace::home.no_records_to_export') ?: 'لا توجد سجلات للتصدير'
            ]);
            return;
        }

        return Excel::download(new MerchantOrderExport($ordersQuery->get())
            , 'Financial Reports.xlsx');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.finance.reports.order.components.export');
    }
}
