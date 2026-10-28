<?php

namespace App\Domain\Notification\Notifications\User;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;


class NotifyUser extends Notification
{
    use Queueable;

    private array $tokens;



    public $title;
    public $body;
    public $order;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tokens, $title,$body,$order)
    {
        $this->tokens = $tokens;
        $this->title = $title;
        $this->body = $body;
        $this->order = $order;


    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage)
            ->withTitle($this->title)
            ->withBody($this->body)
            ->withSound('default')
            ->withAdditionalData([
                'order_id' => $this->order?->id,
                'status' => $this->order?->status,
            ])
            ->asNotification($this->tokens); // OR ->asMessage($deviceTokens);

    }

    public function toDatabase($notifiable)
    {
        return [

            'title' => $this->title,
            'body' => $this->body,
        ];

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'data' => [

                'title' => $this->title,
                'body' => $this->body,
            ],
        ];
    }
}
