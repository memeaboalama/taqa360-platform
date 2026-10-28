<?php

namespace App\Domain\Notification\Jobs\Merchant;




use App\Domain\Notification\Notifications\Merchant\DriverOrder;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifyDriverOrder
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;
    private  $merchant;
    private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $merchant)
    {
        $this->order = $order;
        $this->merchant = $merchant;
        $this->device_tokens =  $this->merchant->device_tokens?->pluck('token')->toArray() ?? [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {
            $this->merchant?->notify(new DriverOrder($this->device_tokens, $this->order));
        }
    }
}
