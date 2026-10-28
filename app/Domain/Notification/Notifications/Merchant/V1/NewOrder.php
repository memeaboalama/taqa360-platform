<?php

namespace App\Domain\Notification\Notifications\Merchant\V1;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\Topics;



class NewOrder extends Notification
{
    use Queueable;

    private $token;
    private $order;

    public $data_notify;
    public $title;
    public $name;
    public $code;
    public $status;
    public $order_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $order)
    {
        $this->token = $token;
        $this->order = $order;
        $this->order_id = $this->order?->id;
        $this->code = $this->order?->code;
        $this->status = $this->order?->status?->value;
        $this->data_notify = [
            'ar' => "لديك طلب جديد ",
            'en' => "You Have a new order request waiting ."
        ];
        $this->title = [
            'ar' => "طلب جديد",
            'en' => "New Order Arrived !"
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

    public function toFirebase($notifiable)
    {
        try {
            $firebase = (new Factory)
                // ->withServiceAccount(__DIR__ . '/../../../../../../config/firebase_credentials.json');
                ->withServiceAccount(config_path('firebase_credentials.json'));
            // ->withServiceAccount(__DIR__.'/../../../../config/firebase_credentials.json');

            $messaging = $firebase->createMessaging();
            $message = CloudMessage::fromArray([
                'token' => $this->token->token,
                'notification' => [
                    'title' => $this->title['ar'],
                    'body' => $this->data_notify['ar'],
                ],
                'data' => [
                    'order_id' =>  $this->order_id ?? $this->order?->id . "",
                    'order_code' =>  $this->code ?? $this->order?->code ?? '',
                    'status' => $this->status ?? $this->order?->status?->value ?? '',
                    'type' => "new_order_request",
                ],
            ]);
            $response = $messaging->send($message);
        } catch (\Throwable $error) {
            Log::error("Merchant Order Notification Error: " . $error->getMessage());
            return $error;
        }
    }

    public function toOldFireOldbaseOld($notifiable)
    {
        $data = [
            'to' => $this->token->token,
            'notification' => [
                'title' => $this->title['ar'],
                'body' => $this->data_notify['ar'],
                'sound' =>  $this->token->platform == 'ios' ? "not.mp3" : "not",
                'android_channel_id' => "eats",
            ],
            'data' => [
                'order_id' => $this->order_id,
                'status' => $this->order?->status,
                'type' => "new_order_request",
            ],
            'content_available' => true,
            'priority' => 'high',
        ];

        $client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/',
            'headers' => [
                'Authorization' => 'Bearer AAAAK3q9AmE:APA91bH0Qb34tEoY2h-hJ5BkVjS2jjntgWhyKJXAbYYdCKRGTiDbKpAUribVldDGSlICrvX3jAQN91DiDRkGncIPG1ilM75KRi5dHx6ZnLbS9yjQRplVpIE3hjI1vC3aXP3XXpDq8Sam',
                'Content-Type' => 'application/json',
            ],
        ]);

        try {

            $response = $client->post('/fcm/send', ['body' => json_encode($data)]);
            // Log::info($response->getBody());
        } catch (\Throwable $error) {
            Log::error("Merchant Order Notification Error: " . $error->getMessage());
            return $error;
        }
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
