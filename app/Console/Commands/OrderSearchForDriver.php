<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Driver\Jobs\SearchForDriverJob;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Support\Carbon;
class OrderSearchForDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-search-for-driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searching for drivers for orders that do not have a driver assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $pastOrdersWithoutDriver = $orderRepo->getOrdersWithoutDriver();
        foreach ($pastOrdersWithoutDriver as $order)
        {
            $this->SearchForDriver($order);
        }

        echo 'About ' . $pastOrdersWithoutDriver->count(). 'orders do not have a driver assigned\n';
        echo Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s') . " now\n";

    }

    protected function SearchForDriver($order){
        dispatch(new SearchForDriverJob($order));//->afterResponse();
    }
}
