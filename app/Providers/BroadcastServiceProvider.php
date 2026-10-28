<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (request()->hasHeader('authorization')){
            Broadcast::routes([ 'middleware' => ['auth:user-api,employee-api,driver-api,admin-api,educator-api'] ]);
        } else {
            Broadcast::routes();
        }

       // Broadcast::routes();
        //Broadcast::routes(['middleware' => ['web','auth']]);
        require base_path('routes/channels.php');

    }
}
