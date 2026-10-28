<?php

namespace App\Domain\Notification\Notifications\User\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class OrderAccepted extends Notification
{
    use Queueable;

    private array $tokens;
    private $order;

    public $data_notify;
    public $title;
    public $name;
    public $code;
    public $order_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tokens, $order)
    {
        $this->tokens = $tokens;
        $this->order = $order;
        $this->order_id = $this->order?->id;
        $this->data_notify = [
            'ar' => "لقد تم قبول الطلب من المتجر وجاري العمل عليه",
            'en' => "Order Accepted and is being worked on."
        ];
        $this->title = [
            'ar' => "طلب مقبول",
            'en' => "Order Accepted !"
        ];

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
            ->withTitle($this->title['ar'])
            ->withBody($this->data_notify['ar'])
            ->withSound('default')
            ->withAdditionalData([
                'order_id' => $this->order?->id,
                'status' => $this->order?->status,
                'type' => "order_accepted",
            ])
            ->asNotification($this->tokens); // OR ->asMessage($deviceTokens);

    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order?->id,
            'title' => $this->title,
            'body' => $this->data_notify,
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
                'order_id' => $this->order?->id,
                'title' => $this->title,
                'body' => $this->data_notify,
            ],
        ];
    }
}
