<?php

namespace App\Domain\Notification\Jobs\Merchant\V1;




use App\Support\Enum\StatusEnum;
use Domain\Notification\Models\DeviceToken;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Domain\MarketPlace\Models\MerchantOrder;

class SendNotifyOrderV2
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $managerId = $this->order->branch->manager_id;
        $branch = MerchantOrder::find($this->order->id)->branch()->first();
        $managerId = $branch->manager_id;
        $manager_device_tokens = DeviceToken::where('deviceable_type', 'Domain\\MarketPlace\\Models\\MerchantEmployee')
            ->Where('deviceable_id', $managerId)
            ->pluck('token')->toArray();
        if (!empty($manager_device_tokens)) {
            $this->send($manager_device_tokens);
        }
    }

    protected function send(array $tokens)
    {


        $firebase = (new Factory)
            // ->withServiceAccount(__DIR__ . '/../../../../../../config/firebase_credentials.json');
            ->withServiceAccount(config_path('firebase_credentials.json'));
        // ->withServiceAccount(__DIR__.'/../../../../config/firebase_credentials.json');

        $messaging = $firebase->createMessaging();

        // 'sound' =>  $this->token->platform == 'ios' ? "not.mp3" : "not",
        // 'android_channel_id' => "eats",
        $message = CloudMessage::fromArray([


            'notification' => [
                'title' => "لديك طلب جديد ",
                'body' => "You Have a new order request waiting .",
            ],
            'data' => [
                'order_id' => "".$this->order->id."",
                'order_code' => $this->order->code,
                'type' => "new_order_request",
            ],
            'apns' => [
                'headers'=>[
                    'order_id' => "".$this->order->id."",
                    'order_code' => $this->order->code,
                    'type' => "new_order_request",
                ],
                'payload' => [
                    'alert'=>[
                        'title'=>"لديك طلب جديد ",
                        'subtitle'=>"طلب جديد ",
                        'body'=>"You Have a new order request waiting .",
                    ],
                    'sound' => "not.mp3",
                    'content-available' => 1
                ]
            ],
            'android' => [
                'notification' => [
                    'title' => "لديك طلب جديد ",
                    'body' => "You Have a new order request waiting .",
                    'channel_id' => "eats",
                    'sound' => "not",
                    'notification_priority' => 'PRIORITY_DEFAULT',
                    'visibility' => 'PUBLIC',
                ],
                'data' => [
                    'order_id' => "".$this->order->id."",
                    'order_code' => $this->order->code,
                    'type' => "new_order_request",
                ],
            ]


        ]);
        //->withHighestPossiblePriority();
        // $message1 = CloudMessage::fromArray([


        //     'notification' => [
        //         'title' => "لديك طلب جديد ",
        //         'body' => "You Have a new order request waiting .",
        //     ],
        //     'data' => [
        //         'order_id' => $this->order->id,
        //         'order_code' => $this->order->code,
        //         'type' => "new_order_request",
        //     ],
        //     'apns' => [
        //         'payload' => [
        //             'sound' => "not.mp3"
        //         ]
        //     ],
        //     'android' => [
        //         'notification' => [
        //             'channel_id' => "eats",
        //             'sound' => "not"
        //         ]
        //     ]


        // ])->withHighestPossiblePriority();
        // $message->withAndroidConfig() = CloudMessage::fromArray($data);
        // 'targetingConditionJson'=>'{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}'
        // 'condition'=>'{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}'

        //select('token')->take(900)->toArray()->
        // Only get the 'token' column

        // $messaging->send($message);
        return  $messaging->sendMulticast($message, $tokens);
    }
}
