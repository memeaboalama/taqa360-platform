<?php

namespace App\Domain\MarketPlace\Jobs;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\Notification\Jobs\Merchant\SendNotifyOrder;
use App\Support\Enum\StatusEnum;
use Domain\AppBase\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderChangeStatusIfPaidedJop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MerchantOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(MerchantOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
   public function handle()
    {

        $order = MerchantOrder::query()->find($this->order?->id);
        if (!$order ||$order?->status != MerchantOrderStatuses::DRAFT)
        return;
        $pay = Payment::Where('payable_id', $order->id)
        ->Where('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder')
        ->where('status', 'paid')
        // ->orderByDesc('updatedAt')->first();
        ->latest('updated_at')->first();
        if(!$pay ||$pay->status !='paid') return;
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction= new OrderAction($orderRepo);
        /// user location as point .....
        $order = $orderAction->update($order->id, MerchantOrderStatuses::PENDING->value)??$order;
        $this->notifyBranchEmployees($order);

    try {
        $this->notifyEmployees($order);
        $this->notifyBranchManager($order);
    } catch (\Exception $exception) {
        dd($exception->getMessage());
        Log::error($exception);
    }
      
    }
    protected function notifyBranchEmployees(MerchantOrder $order) : void
    {
        try{
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrderV2($order));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }
  
    protected function notifyEmployees(MerchantOrder $order) : void
    {
        $employees = $order->branch->employees()->where('status' , StatusEnum::ACTIVE->value)->get();
        foreach ($employees as $employee)
        {
            NewOrderEvent::dispatch($employee, $order);
            // dispatch(new SendNotifyOrder($order, $employee))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee))->afterResponse();
            dispatch_sync(new SendNotifyOrder($order, $employee));
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee));
        }
    }

    protected function notifyBranchManager(MerchantOrder $order) : void
    {
        $manager = $order->branch->manager()->first();
        if ($manager)
        {
            NewOrderEvent::dispatch($manager, $order);
            // dispatch(new SendNotifyOrder($order, $manager))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $manager))->afterResponse();
            dispatch_sync(new SendNotifyOrder($order, $manager));
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $manager));
        }
    }
}
