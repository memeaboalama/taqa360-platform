<?php

namespace App\Domain\Notification\Jobs\User\Order;

use App\Domain\Notification\Notifications\User\Order\OrderAccepted;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAcceptedOrder
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;
    private  $user;
    private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
        $this->device_tokens =  $this->user->device_tokens?->pluck('token')->toArray() ?? [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {
            $this->user?->notify(new OrderAccepted($this->device_tokens, $this->order));
        }
    }
}
