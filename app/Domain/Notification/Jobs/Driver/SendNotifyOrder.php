<?php

namespace App\Domain\Notification\Jobs\Driver;

use App\Domain\Notification\Notifications\Driver\NewOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifyOrder
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;
    private  $driver;
    private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $driver)
    {
        $this->order = $order;
        $this->driver = $driver;
        $this->device_tokens =  $this->driver->device_tokens?->pluck('token')->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {

            $this->driver?->notify(new NewOrder($this->device_tokens, $this->order));
        }
    }
}
