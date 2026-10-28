<?php

namespace App\Domain\User\listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetLastLogin
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->update([
            'last_logged_in_at' => now()
        ]);

        $event->user->logins()->create([
            'logged_in_at'  =>  $event->user->last_logged_in_at
        ]);

    }
}
