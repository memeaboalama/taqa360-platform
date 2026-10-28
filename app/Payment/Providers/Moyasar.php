<?php

namespace App\Payment\Providers;

use App\Payment\PaymentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class Moyasar implements PaymentInterface
{

    public $apiSecret, $apiKey, $apiUrl;

    public function __construct()
    {
        $this->apiSecret = config('services.moyasar.secret');
        $this->apiKey = config('services.moyasar.key');
        $this->apiUrl = config('services.moyasar.url');
    }

    public function data(array $data, Model $model): array
    {
        return [
            'payment_id' => $data["id"],
            'status' => $data["status"],
            'amount' => $data["amount"],
            'fee' => $data["id"],
            'currency' => $data["currency"] ?? "SAR",
            'refunded' => $data["refunded"],
            'refundedAt' => $data["refunded_at"],
            'captured' => $data["captured"],
            'capturedAt' => $data["captured_at"],
            'voidedAt' => $data["voided_at"],
            'description' => $data["description"],
            'amountFormat' => $data["amount_format"],
            'feeFormat' => $data["fee_format"],
            'refundedFormat' => $data["refunded_format"],
            'capturedFormat' => $data["captured_format"],
            'invoiceId' => $data["invoice_id"],
            'ip' => $data["ip"],
            'callbackUrl' => $data["callback_url"],
            'createdAt' => $data["created_at"],
            'updatedAt' => $data["updated_at"],
//            'metadata' => $data["updated_at"],
            'source_type' => isset($data["source"]["type"]) ? $data["source"]["type"] : null,
            'source_company' => isset($data["source"]["company"]) ? $data["source"]["company"] : null,
            'source_name' => isset($data["source"]["name"]) ? $data["source"]["name"] : null,
            'source_number' => isset($data["source"]["number"]) ? $data["source"]["number"] : null,
            'source_message' => isset($data["source"]["message"]) ? $data["source"]["message"] : null,
            'source_transaction_url' => isset($data["source"]["transaction_url"]) ? $data["source"]["transaction_url"] : null,
            'source_gateway_id' => isset($data["source"]["gateway_id"]) ? $data["source"]["gateway_id"] : null,
            'source_reference_number' => isset($data["source"]["reference_number"]) ? $data["source"]["reference_number"] : null,

            'user_id' => $model->user_id,
            'channel' => $model->channel,
        ];

    }

    public function message(int $code): string
    {
        switch ($code) {
            case 400:
                return "Bad Request – The request was unacceptable, often due to missing a required parameter";
            case 401:
                return "Unauthorized – No valid API key provided";
            case 403:
                return "Forbidden – credentials not enough to access resources";
            case 404:
                return "Not Found – The requested resource doesn’t exist";
            case 405:
                return "Method Not Allowed – Entity not activated to use live account";
            case 429:
                return "Too Many Requests – Too many requests hit the API too quickly.";
            case 500:
                return "Internal Server Error – We had a problem with our server. Try again later.";
            case 503:
                return "Service Unavailable – We are temporarily offline for maintenance. Please try again later.";
            default:
                return "pay success";


        }
    }


    public function pay(array $data, Model $model)
    {
        return $this->savePayment(self::data($data, $model), $model);

    }


    public function savePayment(array $postFields, Model $model)
    {

        return $model->payment()->updateOrCreate(['payment_id' => $postFields["payment_id"]], $postFields);

    }

    public function errors(): array
    {
        return [400, 401, 403, 404, 405, 429, 500, 503];
    }


    public function moyasarTypeErrors(): array
    {
        return [
            "invalid_request_error",
            "authentication_error",
            "rate_limit_error",
            "api_connection_error",
            "account_inactive_error",
            "api_error",
            "3ds_auth_error"
        ];
    }


    public function paymentStatus(array $postFields)
    {

        $json = $this->callAPI($postFields["id"], [], 'GET');

        if (!$json["status"]) return false;
        return $json["data"];


    }


    public function applePay(array $postFields)
    {

        $json = $this->callAPI("", $postFields);

        if (!$json["status"]) return false;

        return $json["data"];
    }

    function callAPI($endpointURL, $postFields = [], $requestType = 'POST')
    {
        $response = '';
        if ($requestType === "GET") {
            $response = Http::accept('application/json')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->accept('application/json')
                ->withBasicAuth($this->apiSecret, '')
                ->get($this->apiUrl . $endpointURL);
                // Log::channel('daily')->warning("Moyasar apple_status: " .$response->status() ."data : " .json_encode($postFields));

                // Log::channel('daily')->warning("Moyasar response: " .json_encode($response->json()));
                // Log::channel('daily')->warning("Moyasar body: " .$response->body());
                if ($response->status() === 200 ) {
                    return ["status" => true, 'message' => '', "data" => $response->json()];
                } else {
                    return $this->handleErrorV2($response,$endpointURL, $postFields, $requestType);
                    // return $this->handleError($response);
        
                }

        } elseif ($requestType === "POST") {
            $response = Http::accept('application/json')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->accept('application/json')
                ->withBasicAuth($this->apiSecret, '')
                ->post($this->apiUrl . $endpointURL, $postFields);
                // Log::channel('daily')->info("apple_status: " .$response->status() ."data : " .json_encode($postFields));

                // Log::channel('daily')->info("response: " .json_encode($response->json()));
                // Log::channel('daily')->info("body: " .$response->body());
                if ($response->status() === 201) {
                    return ["status" => true, 'message' => '', "data" => $response->json()];
                } else {
                    return $this->handleErrorV2($response,$endpointURL, $postFields, $requestType);
                    // return $this->handleError($response);
        
                }
        }

        

       


    }


    function handleErrorV2($response,$endpointURL, $postFields = [], $requestType = 'POST')
    {
        // Log::channel('slack')->error("Moyasar apple_status: ".$endpointURL .$response->status() ."data : " .json_encode($postFields));
        // Log::channel('slack')->error("Moyasar endpointURL : {".$endpointURL ."},data : {" .json_encode($postFields)."}, response: { Status: ".$response->status() .",data : " .json_encode($response->json())."}");
        Log::channel('slack')->error("Moyasar RequestType: {".$requestType."}, endpointURL : {".$endpointURL ."}, data : {" .json_encode($postFields)."}, response: { Status: ".$response->status() .",data : " .$response->body()."}");

        // Log::channel('slack')->error("Moyasar response: " .json_encode($response->json()));
        // Log::channel('slack')->error("Moyasar body: " .$response->body());
        $json = json_decode($response);

        if ($json)
            return ['status' => false, 'message' => $json->message ?? '', 'error' => $json->errors ?? null];

    }
    function handleError($response)
    {
        
        $json = json_decode($response);

        if ($json)
            return ['status' => false, 'message' => $json->message ?? '', 'error' => $json->errors ?? null];

    }
}
