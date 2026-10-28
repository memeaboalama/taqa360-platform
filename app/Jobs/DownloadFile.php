<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imageUrl;
    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $imageUrl
     * @param string $filePath
     * @return void
     */
    public function __construct($imageUrl, $filePath)
    {
        $this->imageUrl = $imageUrl;
        $this->filePath = $filePath;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $client = new Client(['verify' => false]);
            $response = $client->get($this->imageUrl);
            file_put_contents($this->filePath, $response->getBody());
        } catch (\Exception $error) {
            Log::error("Error downloading file: " . $error->getMessage());
        }
    }
}
