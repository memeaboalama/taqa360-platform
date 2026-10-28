<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\MarketPlace\Jobs\OrderChangeStatusIfPaidedJop;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderChangeStatusIfPaided extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-change-status-if-paided';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searching for draft orders with paymant paid status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log::warning("begin order-change-status-if-paided");
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        // $orderAction= new OrderAction($orderRepo);
        $pastOrdersWithoutDriver = $orderRepo->getDraftOrdersWithPaymentStatusPaid();
        foreach ($pastOrdersWithoutDriver as $order)
        {
            $this->UpdateStatus($order);
            Log::warning("begin paided order change status from draft to pinding order code : ".$order->code);
        }

        echo 'About ' . $pastOrdersWithoutDriver->count(). 'draft orders with paymant paid status\n';
        echo Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s') . " now\n";

    }

    protected function UpdateStatus($order){
        dispatch(new OrderChangeStatusIfPaidedJop($order))->afterResponse();
        // return Redirect::to("https://app.eats.sa/payment-done?&order_id=".$id."&confirm=failed");

    // try {
    //     $this->notifyEmployees($order);
    //     $this->notifyBranchManager($order);
    // } catch (\Exception $exception) {
    //     dd($exception->getMessage());
    //     Log::error($exception);
    // }
    }

    // protected function notifyEmployees(MerchantOrder $order) : void
    // {
    //     $employees = $order->branch->employees()->where('status' , StatusEnum::ACTIVE->value)->get();
    //     foreach ($employees as $employee)
    //     {
    //         NewOrderEvent::dispatch($employee, $order);
    //         dispatch_sync(new SendNotifyOrder($order, $employee));
    //     }
    // }

    // protected function notifyBranchManager(MerchantOrder $order) : void
    // {
    //     $manager = $order->branch->manager()->first();
    //     if ($manager)
    //     {
    //         NewOrderEvent::dispatch($manager, $order);
    //         dispatch_sync(new SendNotifyOrder($order, $manager));
    //     }
    // }
}
