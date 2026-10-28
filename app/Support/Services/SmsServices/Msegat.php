<?php

namespace App\Support\Services\SmsServices;

use App\Contracts\Services\OtpContract;
use App\Domain\Otp\Models\OtpSetting;

use Illuminate\Support\Facades\Log;

class Msegat
{
    public function send($phone_number, $message,OtpSetting $service_settings)
    {
        try{

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);

            curl_setopt($ch, CURLOPT_POST, TRUE);
            $fields =
                [
                    "userName"      => config('services.messegat.username'),
                    "apiKey"        => config('services.messegat.apikey'),
                    "userSender"    => config('services.messegat.senderid'),
                    "numbers"       => $phone_number,
                    "msg"           => $message
                ];

            // info($fields);
            Log::channel('msegat')->info(json_encode($fields));

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            ));

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);

            $info = curl_getinfo($ch);

            curl_close($ch);

            list($headers, $content) = explode("\r\n\r\n",$response,2);

            Log::channel('msegat')->info("response : $response , message : $message , numbers : " . $phone_number);

            if (json_decode($content,true)['code'] != 'M0000' && json_decode($content,true)['code'] != '1')  {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }
            Log::channel('msegat')->info("response : $response , message : $message , numbers : " . $phone_number);
            return true;

        }
        catch(\Exception $e){
//            trigger_error(sprintf(
//                'Curl failed with error #%d: %s',
//                $e->getCode(), $e->getMessage()),
//                E_USER_ERROR);

            // Log::channel('msegat')->error("Curl failed with error " . $e->getCode() . "  "  . $e->getMessage());
            Log::error("Curl failed with error " . $e->getCode() . "  "  . $e->getMessage());
            Log::channel('msegat')->error("Curl failed with error " . $e->getCode() . "  "  . $e->getMessage());
        }
    }
}
