<?php

namespace App\Domain\Notification\Jobs\Driver;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Domain\Notification\Models\DeviceToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;

class SendNotifyChangeOrderStatusV2
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // private  $order;
    // private  $driver;
    // private array $device_tokens;

    // /**
    //  * Create a new job instance.
    //  *
    //  * @return void
    //  */
    // public function __construct($order, $driver)
    // {
    //     $this->order = $order;
    //     $this->driver = $driver;
    //     // $this->device_tokens =  $this->driver->device_tokens?->pluck('token')->toArray();
    // }

    // private  $order_id;
    private  $driver_id;
    // private array $device_tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct($order_id, $driver_id)
    public function __construct($driver_id)
    {
        // $this->order_id = $order_id;
        $this->driver_id = $driver_id;
        // $this->device_tokens =  $this->driver->device_tokens?->pluck('token')->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $manager_device_tokens = DeviceToken::where('deviceable_type', 'Domain\\Driver\\Models\\Driver')
            // ->Where('deviceable_id', $this->driver->id)
            ->Where('deviceable_id', $this->driver_id)
            ->pluck('token')->toArray();
        if (!empty($manager_device_tokens)) {
            // $this->send($manager_device_tokens, $this->order);
            $this->send($manager_device_tokens);
        }
        // Log::debug($manager_device_tokens);
        // if (!empty($this->device_tokens)) {

        //     $this->driver?->notify(new StatusOrder($this->device_tokens, $this->order));
        // }
    }
    // protected function send($tokens, $order)
    protected function send($tokens)
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
                'title' => "تم تغيير الحالة",
                'body' => "تم تغيير حالة الطلب",
            ]
        ]);
        return  $messaging->sendMulticast($message, $tokens);
    }
}
