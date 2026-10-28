<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetTodayImageFromAppServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-today-image-from-app-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A daily backup of images on the app server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(env('BACKUP_ENABLED') == true){
            dispatch(new \App\Jobs\GetTodayImageFromAppServer(env('BACKUP_URL')));
        }
    }
}
