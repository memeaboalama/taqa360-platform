<?php

namespace App\Domain\Otp\Jobs;

use App\Support\Services\OtpServices\SendSmsOTP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class QueueSmsOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $model;
    private string $message;


    /**
     * Create a new job instance.
     */
    public function __construct(Model $model ,string $message)
    {
        //
        $this->model = $model;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(SendSmsOTP $sendSmsOTP): void
    { 
        $sendSmsOTP->send($this->model?->phone_number,$this->message);
    }
}
