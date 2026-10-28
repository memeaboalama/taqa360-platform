<?php

namespace App\Domain\ElmApp\Actions\Order;

use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Database\Eloquent\Model;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\AppBase\Models\Offer;
use App\Domain\AppBase\Repositories\PromocodeRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Domain\Otp\Repositories\OtpSettingRepository;
use App\Support\Services\SmsServices\Msegat;

class UpdateOrderStatusAction
{
    private MerchantOrderRepository $merchantOrderRepository;
    private LogOrderHistoryAction $logOrderHistoryAction;
    
    public WalletOrderAction $walletOrderAction;
    public PromocodeRepository $promocodeRepository;
    public OtpSettingRepository $otpSettingRepository ;
    public Msegat $msegat;
    public  $defaultOtp;
    public function __construct(MerchantOrderRepository $merchantOrderRepository,LogOrderHistoryAction $logOrderHistoryAction,WalletOrderAction $walletOrderAction,PromocodeRepository $promocodeRepository,OtpSettingRepository $otpSettingRepository , Msegat $msegat)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
        $this->walletOrderAction = $walletOrderAction;
        $this->promocodeRepository = $promocodeRepository;
        $this->otpSettingRepository = $otpSettingRepository;
        $this->msegat = $msegat;
        $this->defaultOtp = $otpSettingRepository->getDefaultOtpSetting();
    }
    public function handle($order_id ,array $data ,Model $user, $reason = "Order Status Update Manually By Admin")
    {
        // $reason = "Order Status Update Manually By Admin";
        $this->logOrderHistoryAction->handle(
            $order_id,
            $data['status'],
            $reason,
            $user,
        );
        if ($data["status"] == MerchantOrderStatuses::PREVIOUS->value)
        {
            $order = MerchantOrder::where('id',$order_id )->first();
            $orderCount = MerchantOrder::where('user_id',$order->user_id )->count();
            if($order->cash_back_id)
              $this->walletOrderAction->add_cash_back($order, "Wallet Order Cash Back") ; 
            if($orderCount==1)
            {
               $offerFrist = Offer::where('id','1' )
               ->where('status', '=', 'active')
               ->where('usages_left', '>', 0)
               ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))->first();
             if($offerFrist)
               {
                // $codeNew ='OFFER-'.rand(1000,9999);
                $codeNew ='OF'.$order_id;
                $currentDate = Carbon::now();
                $expired = $currentDate->addDays($offerFrist->period,);
                $dataPromcode = [
                    'code' => $codeNew,
                    'usages_left' => $offerFrist->usages_left,
                    'limit_per_user' => $offerFrist->limit_per_user,
                    'min_order_value' => $offerFrist->min_order_value,
                    'max_discount' => $offerFrist->max_discount,
                    'period' => $offerFrist->period,
                    'type' => $offerFrist->type,
                    'type_discount' => $offerFrist->type_discount,
                    'discount_value' => $offerFrist->discount_value,
                    'status' => $offerFrist->status,
                    "sales_agent_id"=> $offerFrist->sales_agent_id,
                    'merchant_id' => $offerFrist->merchant_id,
                    'is_custom' => $offerFrist->is_custom,
                    'expired_at' => $expired,
                    'source' => 'Offer'
                ];
                DB::beginTransaction(); 
                try
                {
                $promcode = $this->promocodeRepository->store($dataPromcode);
                $this->send($order->client_phone,$offerFrist->note." ".$promcode->code); 
                     
                    }
                     catch (\Exception $exception) {                       
                        Log::error($exception->getMessage()); 
                    }
                DB::commit();
              }
            }
        }
        return $this->merchantOrderRepository->update($order_id , $data);
    }
    public function send($phone_number, $message)
    {
        if ($this->defaultOtp?->service_name == 'msegat') {
            return $this->msegat->send($phone_number,$message,$this->defaultOtp);
        }else{
            return $this->msegat->send($phone_number,$message,$this->defaultOtp);

        }

    }
}
