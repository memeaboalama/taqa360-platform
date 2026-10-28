<?php

namespace App\Http\Controllers;
use App\Domain\AppBase\Models\Car\CarsType;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ElmController extends Controller
{


    public function send()
    {
        $order = MerchantOrder::find(1097);
        // $manager = $order->branch->manager()->first();
        // if ($manager)
        // {
        //     try {
        //         NewOrderEvent::dispatch($manager, $order);
        //         $kjdfbc = dispatch_sync(new SendNotifyOrder($order, $manager));
        //     } catch (\Throwable $th) {
        //         return  $th;
        //     }
            
        // }
        // DD($order->orders_history->action);


        return $order->orders_history;
    }

    

    public function sendi()
    {
        $userData = "SHHHHHHHHHSHHHSGGGSUYL";
    
        // Fetch CSRF token using Laravel's csrf_token() function
        // $csrfToken = csrf_token();
    
        // // Set headers in associative array format
        // $headers = [
        //     'Content-Type' => 'application/json',
        //     'X-CSRF-TOKEN' => $csrfToken,
        // ];
    
        $client = new Client(['verify' => false]);
    
        $response = $client->post('http://127.0.0.1:8001/create', [
            // 'headers' => $headers,
            'json' => [$userData], // Guzzle will automatically encode as JSON
        ]);
        // $url = "https://tawseelapi.ecloud.sa/api/Driver/create";
        // $response = $client->post($url, [
        //     'body' => json_encode([
        //         "credential"=>[
        //         "companyName"=> "Eats",
        //         "password" => "X4fBkCMYehTHjUr96VRtEGhpfQgqSAJwGPzzwx1WoVGVOTvLBr8ENXUsvYGtazlY"
        //         ],
        //         "driver"=>[
        //             "identityTypeId"=> "NV25GlPuOnQ=",
        //             "idNumber"=> "1016990911",
        //             "dateOfBirth"=> 19900419,
        //             "registrationDate"=> "2020-04-02T17:41:59.277Z",
        //             "mobile"=> "0552547848",
        //             "regionId"=> "NV25GlPuOnQ=",
        //             "carTypeId"=> "oIcaYzeDfQQ=",
        //             "cityId"=> "NV25GlPuOnQ=",
        //             "carNumber"=> "1234ABC",
        //             "vehicleSequenceNumber"=> "123456789",
        //         ]
        //     ]),
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ]
        // ])->getBody();

        // dd($response);
        // return $response;
        // $response = Http::withOptions([
        //     'proxy' => '172.65.64.100:6060:deqrxfx67zyp2gm-country-sa-session-e2xqy8zcqa-lifetime-120:mujstolba2rfxz2'
        //  ])->withHeaders([
        //     'Content-Type' => 'application/json'
        //  ])->post("https://demo-apitawseel.naql.sa/api/Driver/create",[
        //             'body' => json_encode([
        //                 "credential"=>[
        //                 "companyName"=> "Eats",
        //                 "password" => "X4fBkCMYehTHjUr96VRtEGhpfQgqSAJwGPzzwx1WoVGVOTvLBr8ENXUsvYGtazlY"
        //                 ],
        //                 "driver"=>[
        //                     "identityTypeId"=> "NV25GlPuOnQ=",
        //                     "idNumber"=> "1016990911",
        //                     "dateOfBirth"=> 19900419,
        //                     "registrationDate"=> "2020-04-02T17:41:59.277Z",
        //                     "mobile"=> "0552547848",
        //                     "regionId"=> "NV25GlPuOnQ=",
        //                     "carTypeId"=> "oIcaYzeDfQQ=",
        //                     "cityId"=> "NV25GlPuOnQ=",
        //                     "carNumber"=> "1234ABC",
        //                     "vehicleSequenceNumber"=> "123456789",
        //                 ]
        //             ]),
        //             'headers' => [
        //                 'Content-Type' => 'application/json'
        //             ]
        //         ]);
    }
    
   
    // public function send()
    // {

    //     $userData = "SHHHHHHHHHSHHHSGGGSUYL";
    //     $response = Http::get('http://127.0.0.1:8001/csrf-token');

    //     if ($response->successful()) {
    //         $csrfToken = $response->json('token');

    //         // Log CSRF token
    //         \Log::info('CSRF Token from Receiving Project: ' . $csrfToken);

    //         try {

    //             $headers =  [
    //                 'content-type' => 'application/json',
    //                 'verify' => true,
    //                 'X-CSRF-TOKEN' => csrf_token()
    //             ];

    //             $client = new Client();
    //             $response = $client->POST('http://127.0.0.1:8001/create', [
    //                 'body' => json_encode([$userData]),
    //                 'headers' => $headers,
    //            ]);
              
    //         //    dd($response);
    //             // $response = Http::withHeaders([
    //             //     'X-CSRF-TOKEN' => $csrfToken,
    //             // ])->post('http://127.0.0.1:8001/create',[$userData]);

    //             // Check if the response is successful
    //             // if ($response->successful()) {
    //             //     return $response->json();
    //             // } else {
    //             //     return response()->json(['error' => 'Failed to send data'], 500);
    //             // }

    //         } catch (\Exception $e) {
    //             \Log::error('Error sending data: ' . $e->getMessage()); // Log the error
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //     } else {
    //         return response()->json(['error' => 'Failed to fetch CSRF token from receiving project'], 500);
    //     }
    //     // $csrfToken = $response;
    //     // Log CSRF token
    //     // \Log::info('CSRF Token: ' . $csrfToken);

    //     // try {
    //     //     $response = Http::post('http://127.0.0.1:8001/create' ,[$userData]); // Specify the new URL

    //     //     // Check if the response is successful
    //     //     // if ($response->successful()) {
    //     //     //     return $response->json();
    //     //     // } else {
    //     //     //     return response()->json(['error' => 'Failed to send data'], 500);
    //     //     // }

    //     // } catch (\Exception $e) {
    //     //     \Log::error($e->getMessage()); // Log the error
    //     //     return response()->json(['error' => $e->getMessage()], 500);
    //     // }
        

    //     // return json_decode($response->body(), true);    
        
    //     return $response;
    
    // }


    public function sends()
    {
        $apiUrl = 'http://127.0.0.1:8001/create'; // Replace with actual API URL

        $client = new Client(['verify' => false]);
        $response = $client->post($apiUrl,["AS"=>'HHHGGGGGSGGSGGGSG']);

        // if ($response->getStatusCode() === 200) {
        //     $userData = json_decode($response->getBody(), true);

        //     // Display user details on the profile page
        //     // return view('user-profile', ['userData' => $userData]);
        // } else {
        //     // Handle API error response (e.g., user not found)
        //     return redirect('/')->with('error', 'Failed to fetch user details.');
        // }

        return json_decode($response->getBody(), true);
    }

    public function editUserProfile($userId)
    {
        $apiUrl = 'https://project1-api-domain/api/users/' . $userId; // Replace with actual API URL

        $client = new Client();
        $response = $client->request('GET', $apiUrl);

        if ($response->getStatusCode() === 200) {
            $userData = json_decode($response->getBody(), true);

            return view('edit-user-profile', ['userData' => $userData]);
        } else {
            return redirect('/')->with('error', 'Failed to fetch user details.');
        }
    }

    public function updateUserProfile($userId, Request $request)
    {
        $apiUrl = 'http://127.0.0.1:8001/users/' . $userId; // Replace with actual API URL

        $client = new Client();
        $response = $client->request('PUT', $apiUrl, [
            'body' => json_encode([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]),
        ]);

        if ($response->getStatusCode() === 200) {
            // User data updated successfully
            return redirect('/user-profile/' . $userId)->with('success', 'User profile updated.');
        } else {
            // Handle API error response (e.g., validation errors)
            return redirect('/edit-user-profile/' . $userId)->with('error', 'Failed to update user profile.');
        }
    }

}




    // protected function discount(): Attribute
    // {
    //     $check = $this->validCoupon();
    //     return new Attribute(
    //         get: fn() => ($this->promocode && $check) ?
    //             (                        
    //                 ($check->type == "flat") ?
    //                     (
    //                         ($check->type_discount == "total") ?
    //                             (
    //                                 ($check->discount_value >= $this->total) ? $this->total : $check->discount_value  
    //                             )
    //                             :
    //                             ($check->type_discount == "delivery") ? 
    //                             (
    //                                 ($check->discount_value >= $this->delivery_fee) ? $this->delivery_fee = 0 :
    //                                     ($this->delivery_fee - $check->discount_value)
    //                             )
    //                             :  
    //                             ($check->discount_value >= $this->subtotal) ? $this->subtotal : $check->discount_value
    //                     )
    //                     :
    //                     (
    //                         ($check->type_discount == "total") ?
    //                             (
    //                                 ($this->total * ($check->discount_value / 100) >= $check->max_discount) ?
    //                                     $check->max_discount : $this->total * ($check->discount_value / 100)
    //                             )
    //                             :
    //                             ($check->type_discount == "delivery") ? 
    //                             (
    //                                 ($this->delivery_fee * ($check->discount_value / 100) >= $check->max_discount) ? 
    //                                     ($this->delivery_fee =  $check->max_discount) : ($this->delivery_fee = $this->delivery_fee * ($check->discount_value / 100))
    //                             )
    //                             :  
    //                             ($this->subtotal * ($check->discount_value / 100) >= $check->max_discount) ? $check->max_discount : $this->subtotal * ($check->discount_value / 100)
    //                     )
    //             )
    //             : 0
    //     );
    // }
    