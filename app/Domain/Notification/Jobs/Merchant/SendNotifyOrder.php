<?php

namespace App\Domain\Notification\Jobs\Merchant;




use App\Domain\Notification\Notifications\Merchant\NewOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifyOrder
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;
    private  $employee;
    private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $employee)
    {
        $this->order = $order;
        $this->employee = $employee;
        $this->device_tokens =  $this->employee->device_tokens?->pluck('token')->toArray() ?? [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {
            $this->employee?->notify(new NewOrder($this->device_tokens, $this->order));
        }
    }
}
