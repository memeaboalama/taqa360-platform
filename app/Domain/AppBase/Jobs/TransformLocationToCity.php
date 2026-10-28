<?php

namespace App\Domain\AppBase\Jobs;

use App\Domain\AppBase\Repositories\CityRepository;
use App\Domain\AppBase\Repositories\DistractRepository;
use App\Support\Services\OtpServices\SendSmsOTP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransformLocationToCity
{
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Dispatchable, InteractsWithQueue, SerializesModels;

    private array $names;


    /**
     * Create a new job instance.
     */
    public function __construct(array $names)
    {
        $this->names = $names;
    }

    /**
     * Execute the job.
     */
    public function handle(CityRepository $cityRepository, DistractRepository $distractRepository): void
    {
        $city = $cityRepository->firstOrCreate($this->names);
        if($city) $distractRepository->firstOrCreate($city, $this->names);

    }
}
