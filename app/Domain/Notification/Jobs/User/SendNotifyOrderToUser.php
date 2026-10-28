<?php

namespace App\Domain\Notification\Jobs\User;




use App\Domain\Notification\Notifications\User\NotifyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifyOrderToUser
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $title,$body;
    private  $user;
    private  $order, $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$body,$order, $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
        $this->order = $order;
        $this->device_tokens =  $this->user->device_tokens?->pluck('token')->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {
            $this->user?->notify(new NotifyUser($this->device_tokens, $this->title,$this->body,$this->order));
        }
    }
}
