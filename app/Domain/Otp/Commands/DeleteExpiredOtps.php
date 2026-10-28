<?php

namespace App\Domain\Otp\Commands;

use Domain\Otp\Repositories\OtpRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-otps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired otps from database';

    /**
     * Execute the console command.
     */
    public function handle(OtpRepository $otpRepository)
    {
        $otpRepository->delete_expired_codes();

        Log::info('done');
    }
}
