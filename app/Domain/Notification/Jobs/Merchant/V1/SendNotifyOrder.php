<?php

namespace App\Domain\Notification\Jobs\Merchant\V1;




use App\Domain\Notification\Notifications\Merchant\V1\NewOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Concerns\ToArray;

class SendNotifyOrder
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;
    private  $employee;
    private  $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $employee)
    {
        $this->order = $order;
        $this->employee = $employee;
        $this->device_tokens = $this->employee?->device_tokens ?? [];

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->device_tokens->isNotEmpty()) 
        {
            foreach($this->device_tokens as $token){
                $this->employee->notify(new NewOrder($token, $this->order));
            }
        }
    }
}
