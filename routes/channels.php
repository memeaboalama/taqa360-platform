<?php

use App\Domain\Provider\Models\ProviderOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/



Broadcast::channel('drivers.{id}', function ($driver, $id) {
    return (int) $driver?->id === (int)$id;
//    return true;
});

