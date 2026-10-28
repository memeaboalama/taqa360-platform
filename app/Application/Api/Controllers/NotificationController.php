<?php

namespace App\Application\Api\Controllers;

// use App\Domain\AppBase\Models\Location\City;
// use App\Domain\AppBase\Models\Location\Region;
use App\Http\Controllers\Controller;
// use App\Support\Traits\Api\ApiResponseTrait;
// use Illuminate\Http\JsonResponse;
use Domain\Notification\Models\DeviceToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
class NotificationController extends Controller
{
    // use ApiResponseTrait;
    public function sendPushNotification($data)
    {
        $tokens = DeviceToken::where('deviceable_type', $data['device_type'])
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return response()->json(['message' => 'No device tokens found for this audience']);
        }

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__.'/../../../../config/firebase_credentials.json');
 
        $messaging = $firebase->createMessaging();
 
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' =>  $data['title'],
                'body' => $data['body']
            ],
        ]);
        // $message = CloudMessage::fromArray($data);
        // 'targetingConditionJson'=>'{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}'
        // 'condition'=>'{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}'

        // $messaging->send($message);
        $messaging->sendMulticast($message, $tokens);

        return response()->json(['message' => 'Push notification sent successfully']);
    }
}
