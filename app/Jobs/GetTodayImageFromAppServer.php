<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class GetTodayImageFromAppServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $baseUrl;
    /**
     * Create a new job instance.
     */

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Execute the job.
     */

    public function handle()
    {
        $apiUrl = $this->baseUrl."/today-image-urls";

        try {
            $client = new Client(['verify' => false]);
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ])->getBody();

            $imageUrls = json_decode($response, true);

            foreach ($imageUrls as $url) {
                $filename = basename($url['image_url']);
                $filePath = 'backup/app-server/files/' . $filename;

                if (!file_exists($filePath)) {
                    DownloadFile::dispatch($url['image_url'], $filePath);
                } else {
                    Log::info("File already exists: $filename");
                }
            }
        } catch (\Throwable $error) {
            Log::error("Error handling image download job: " . $error->getMessage());
        }
    }
    
}
