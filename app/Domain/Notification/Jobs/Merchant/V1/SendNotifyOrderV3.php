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
use Exception;
use Illuminate\Support\Facades\Log;

class SendNotifyOrderV3
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order_id = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $managerId = $this->order->branch->manager_id;
        $order = MerchantOrder::find($this->order_id)->first();
        $branch = MerchantOrder::find($this->order_id)->branch()->first();
        $managerId = $branch->manager_id;
        $manager_device_tokens = DeviceToken::where('deviceable_type', 'Domain\\MarketPlace\\Models\\MerchantEmployee')
            ->Where('deviceable_id', $managerId)
            ->pluck('token')->toArray();
        if (!empty($manager_device_tokens)) {
            $this->send($manager_device_tokens, $order);
        }
    }

    protected function send($tokens, $order)
    {


        $firebase = (new Factory)
            // ->withServiceAccount(__DIR__ . '/../../../../../../config/firebase_credentials.json');
            ->withServiceAccount(config_path('firebase_credentials.json'));
        // ->withServiceAccount(__DIR__.'/../../../../config/firebase_credentials.json');

        $messaging = $firebase->createMessaging();


        //    try{
        //     $advance_message = CloudMessage::fromArray([
        //         'notification' => [
        //             'title' => "لديك طلب جديد ",
        //             'body' => "You Have a new order request waiting .",
        //         ],
        //         'data' => [
        //             'order_id' => "".$order->id."",
        //             'order_code' => $order->code,
        //             'type' => "new_order_request",
        //         ],
        //         'apns' => [
        //             'headers'=>[
        //                 'order_id' => "".$order->id."",
        //                 'order_code' => $order->code,
        //                 'type' => "new_order_request",
        //             ],
        //             'payload' => [
        //                 'alert'=>[
        //                     'title'=>"لديك طلب جديد ",
        //                     'subtitle'=>"طلب جديد ",
        //                     'body'=>"You Have a new order request waiting .",
        //                 ],
        //                 'sound' => "not.mp3",
        //                 'content-available' => 1
        //             ]
        //         ],
        //         'android' => [
        //             'notification' => [
        //                 'title' => "لديك طلب جديد ",
        //                 'body' => "You Have a new order request waiting .",
        //                 'channel_id' => "eats",
        //                 'sound' => "not",
        //                 'notification_priority' => 'PRIORITY_DEFAULT',
        //                 'visibility' => 'PUBLIC',
        //             ],
        //             'data' => [
        //                 'order_id' => "".$order->id."",
        //                 'order_code' => $order->code,
        //                 'type' => "new_order_request",
        //             ],
        //         ]
        //     ]);
        //     $messaging->sendMulticast($advance_message, $tokens);
        // }
        // catch (Exception $exception){
        //     Log::info($exception->getMessage());
        // }
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => "لديك طلب جديد ",
                'body' => "You Have a new order request waiting .",
            ],
            'data' => [
                'order_id' => "" . $order->id . "",
                'order_code' => $order->code,
                'type' => "new_order_request",
            ],
        ]);
        return $messaging->sendMulticast($message, $tokens);
    }
}
