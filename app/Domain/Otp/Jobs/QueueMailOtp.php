<?php

namespace App\Domain\Otp\Jobs;

use App\Mail\Marketplace\ConfirmMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QueueMailOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Timeout in seconds for this job.
     */
    public int $timeout = 60;

    public function __construct(
        private readonly string $email,
        private readonly int    $code,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(new ConfirmMail($this->code));
        } catch (\Throwable $e) {
            Log::error('QueueMailOtp: Failed to send OTP email to ' . $this->email . ': ' . $e->getMessage());
            throw $e; // re-throw so the queue can retry
        }
    }
}
