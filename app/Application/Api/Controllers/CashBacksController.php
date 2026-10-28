<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\CashBack;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;


class CashBacksController extends Controller
{
    use ApiResponseTrait;
    public $id;
    public function getCashBacks() :JsonResponse
    {
        //  sss
        return $this->responseData([
            'cash_backs' => CashBack:: where('status', '=', 'active')
        ->where('usages_left', '>', 0)
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->whereNull("merchant_id")
        ->where('service_type_id',1)
        ->get()
        ]);
        
    }
    public function getCashBacksByTypeServiceId($type_service_id) :JsonResponse
    {
        //  sss
        return $this->responseData([
            'cash_backs' => CashBack:: where('status', '=', 'active')
        ->where('usages_left', '>', 0)
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->whereNull("merchant_id")
        ->where('service_type_id',$type_service_id)
        ->get()
        ]);
        
    }
    public function getCashBacksByMerchantId($id) :JsonResponse
    {
        //  sss
        $this->id=$id;
        return $this->responseData([
            'cash_backs' => CashBack:: where('status', '=', 'active')
            ->where(function ($query) {
                $query->where('merchant_id', $this->id)
                      ->orWhere('merchant_id', null);
            })
        ->where('usages_left', '>', 0)
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->where('service_type_id',1)
        ->get()
        ]);
        
    }
    public function getCashBacksByMerchantIdAndTypeServiceId($id,$type_service_id) :JsonResponse
    {
        //  sss
        $this->id=$id;
        return $this->responseData([
            'cash_backs' => CashBack:: where('status', '=', 'active')
            ->where(function ($query) {
                $query->where('merchant_id', $this->id)
                      ->orWhere('merchant_id', null);
            })
        ->where('usages_left', '>', 0)
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->where('service_type_id',$type_service_id)
        ->get()
        ]);
        
    }
}
