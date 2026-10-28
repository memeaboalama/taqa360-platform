<?php

namespace App\Application\Company\Exports\Drivers;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DriverTotalExport implements FromCollection , WithHeadings
{
    public $driver;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function collection()
    {
        return collect([0 =>[
            'id' => $this->driver['id'],
            'Driver Name' => $this->driver['name'],
            'Phone' => $this->driver['phone'],
            'cashOrdersCount' => $this->driver['cashOrdersCount'] ?? 0,
            'totalCashOrders' => $this->driver['totalCashOrders'] ?? 0,
            'totalCashCommissionCollected' => $this->driver['totalCashCommissionCollected'] ?? 0,
            'cashCommissionDueToDriver' => $this->driver['cashCommissionDueToDriver'] ?? 0,
            'onlinePaymentOrdersCount' => $this->driver['onlinePaymentOrdersCount'] ?? 0,
            'totalOnlinePaymentOrders' => $this->driver['totalOnlinePaymentOrders'] ?? 0,
            'totalOnlinePaymentCommissionCollected' => $this->driver['totalOnlinePaymentCommissionCollected'] ?? 0,
            'onlinePaymentCommissionDueToDriver' => $this->driver['onlinePaymentCommissionDueToDriver'] ?? 0,
            'Total Orders' => $this->driver['totalOrders'] ?? 0,
            'Total Collected' => $this->driver['totalCollected'] ?? 0,
            'Total Commission' => $this->driver['totalCommission'] ?? 0,
            'totalDueToDriver' => $this->driver['totalDueToDriver'] ?? 0,
        ]]);
    }

    public function headings() : array
    {
        return [
            "#",
            __('company::home.Driver Name'),
            __('company::home.Phone'),
            __('company::home.cashOrdersCount'),
            __('company::home.totalCashOrders'),
            __('company::home.totalCashCommissionCollected'),
            __('company::home.cashCommissionDueToDriver'),
            __('company::home.onlinePaymentOrdersCount'),
            __('company::home.totalOnlinePaymentOrders'),
            __('company::home.totalOnlinePaymentCommissionCollected'),
            __('company::home.onlinePaymentCommissionDueToDriver'),
            __('company::home.Total Orders'),
            __('company::home.Total Collected'),
            __('company::home.Total Commission'),
            __('company::home.totalDueToDriver'),
        ];
    }
}