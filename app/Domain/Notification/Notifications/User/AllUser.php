<?php

namespace App\Domain\Notification\Notifications\User;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\Topics;



class AllUser extends Notification
{
    use Queueable;

    private $token;
    private $notification;

    public $data_notify;
    public $title;
    public $name;
    public $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $notification)
    {
        $this->token = $token;
        $this->notification = $notification;
        $this->data_notify = [$this->notification->body ];
        $this->title = [ $this->notification->title];

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
        $data = [
            'registration_ids' => $this->token,
            'notification' => [
                'title' => $this->title['ar'],
                'body' => $this->data_notify['ar'],
                'sound' =>  $this->token->platform == 'ios' ? "not.mp3" : "not",
                'android_channel_id' => "eats",
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
            Log::error("User All Notification Error: " . $error->getMessage());
            return $error;
        }
    }


    

    public function toDatabase($notifiable)
    {
        return [
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
                'title' => $this->title,
                'body' => $this->data_notify,
            ],
        ];
    }
}
