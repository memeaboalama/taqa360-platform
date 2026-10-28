<?php

namespace App\Domain\Notification\Jobs\User;

use App\Domain\Notification\Notifications\User\AllUser;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $title,$body,$platform;
    private  $user;
    private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$body, $platform, $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
        $this->platform = $platform;
        $this->device_tokens =  $this->user->device_tokens->where('platform', $this->platform)?->pluck('token')->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->device_tokens)) {
            $this->user?->notify(new AllUser($this->device_tokens, $this->title));
        }
    }
}
