<?php

namespace App\Console\Commands;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use Illuminate\Support\Facades\Log;
class ExpireDeadlinedOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire:orders --force';

    /** 
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finishing requests that are past due time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $pastDueTimeOrders = $orderRepo->getPastDueTimeOrders();
        foreach ($pastDueTimeOrders as $order)
        {
            $this->markOrderAsExpired($order);
        }

        echo 'About ' . $pastDueTimeOrders->count(). ' orders are EXPIRED\n';
        echo Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s') . " now\n";

    }

    protected function markOrderAsExpired($order)
    {
        $isNotDraft = $order->status == MerchantOrderStatuses::CANCELLED ? true : false;
        $order->forceFill([
            'status' => MerchantOrderStatuses::EXPIRED->value,
            'expired_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();
        if($isNotDraft){
            $this->refundOrderAmount($order->id);
        }
    }

    protected function refundOrderAmount($order_id){
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $walletOrderAction = new WalletOrderAction(); 
        $order = $orderRepo->findByCols(['id' => $order_id]);
        $walletOrderAction->deposit($order, "Wallet Canceled Order");
    }
}
