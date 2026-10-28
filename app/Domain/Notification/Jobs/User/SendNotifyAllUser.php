<?php

namespace App\Domain\Notification\Jobs\User;




use App\Domain\Notification\Notifications\User\AllUser;
use Carbon\Traits\ToStringFormat;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Log;
use App\Domain\User\Models\User;
// use Domain\Notification\Models\DeviceToken;
class SendNotifyAllUser
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $notification;
    private  $user;
    private  $tokens;
    private  $device_tokens=[];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification, $token)
    {
        $this->notification = $notification;
        // $this->user = $users;
         $this->tokens = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->tokens  as $token )
        {
            $user = User::where('device_token', $token)->first();
        if ( $user) 
        {
            $user->notify(new AllUser($token, $this->notification));
            
        }
    }
    }
}
