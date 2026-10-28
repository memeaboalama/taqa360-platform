<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;


class UserPoliciesController extends Controller
{
    use ApiResponseTrait;
    public function getUserPolicies() :JsonResponse
    {
        //  sss
        return $this->responseData([
            'UserPolicies' => GeneralSetting::select('user_page_content_en','user_page_content_ar')
        ->get()
        ]);
        
    }
   
}
