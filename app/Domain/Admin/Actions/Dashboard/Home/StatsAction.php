<?php

namespace App\Domain\Admin\Actions\Dashboard\Home;


use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsAction
{
    protected MerchantRepository $merchantRepository;
    protected MerchantOrderRepository $merchantOrderRepository;


    public function __construct(
        MerchantRepository $merchantRepository,
        MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantRepository = $merchantRepository;
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function handle() : array
    {
        return Cache::remember('dashboard_stats', 60, function () {
            return [
                'pendingDriversCount' => DB::table('drivers')->where('is_complete', 0)->count(),
                'customersCount' => DB::table('users')->count(),
                'companiesCount' => DB::table('companies')->count(),
                'merchantsCount' => DB::table('merchants')->count(),
                'pendingMerchantsCount' => DB::table('merchants')
                    ->where('status', StatusEnum::INACTIVE->value)
                    ->whereRaw('(SELECT COUNT(*) FROM merchant_files WHERE merchant_files.fileable_id = merchants.id 
                    and fileable_type=\'Domain\MarketPlace\Models\Merchant\') < 8')
                    ->count(),
                'driversCount' => DB::table('drivers')->count(),
                'onlineDriversCount' => DB::table('drivers')->where('network_status', 1)->count(),
                'ordersCount' => DB::table('merchant_orders')->count(),
                'salesCount' => DB::table('merchant_orders')->sum('total_after_discount'),
                'messagesCount' => DB::table('messages')->count(),
                'ordersByMonth' => $this->merchantOrderRepository->getOrdersByMonth(),
                'salesByMonth' => $this->merchantOrderRepository->getSalesByMonth(),
            ];
        });
    }
}
