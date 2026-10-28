<?php

namespace Application\Marketplace\Controllers;



use App\Application\Marketplace\requests\Order\SavePaymentRequest;
use App\Application\Marketplace\requests\Wallet\SaveWalletPaymentRequest;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletDepositAction;
use App\Domain\MarketPlace\resources\Cart\CartResource;
use App\Domain\User\Models\User;
use App\Domain\User\resources\UserResource;
use App\Domain\User\resources\WalletResource;
use App\Http\Controllers\Controller;
use App\Payment\Payment;
use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class WalletDepositController extends Controller
{
    use ApiResponseTrait;
    public PaymentAction $paymentAction;
    public WalletDepositAction $walletDepositAction;

    public function __construct(PaymentAction $paymentAction ,WalletDepositAction $walletDepositAction)
    {
        $this->paymentAction = $paymentAction;
        $this->walletDepositAction = $walletDepositAction;
    }

    
    public function paymentIframe($lang, $token,WalletDepositAction $walletDepositAction)
    {
        return abort(404);
        // $walletDeposit = $this->walletDepositAction->handle($token);
        // if ($walletDeposit) return view('marketplace::pages.payment.walletDeposit', get_defined_vars());
        // return abort(404);
    }

    public function payment_save(SaveWalletPaymentRequest $request, WalletDepositAction $walletDepositAction, PaymentAction $paymentAction): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $walletDeposit = $walletDepositAction->handle($data["key"]);
        $user = User::find($walletDeposit->user_id);
        $userWallet = 'UID_'.$user->id.'_Main';    
        $confirmed=false;
        if($data["status"]&&$data["status"] == 'paid')
        $confirmed=true;
        
        if(!$walletDeposit) return response()->json(['message' => 'data not found'], 404);
        if (!$user->hasWallet($userWallet)) {        
            $wallet = $user->createWallet([
                'name' => str_replace('_', ":", $userWallet),
                'description' => "User Main Wallet",
                'slug' => $userWallet,
            ]);
            $wallet->depositFloat($walletDeposit->amount , [
                'type' => "From Online Payment",
                'description' => []
            ],$confirmed);
        }else{
            $wallet = $user->getWallet($userWallet);
            $wallet->depositFloat($walletDeposit->amount , [
                'type' => "From Online Payment",
                'description' => []
            ],$confirmed);
        }
        $paymentAction->handle($data, $walletDeposit);

        return response()->json([], 201);
    }

    public function payment_check(Request $request, PaymentAction $paymentAction , WalletDepositAction $walletDepositAction)
    {

        $data = $paymentAction->checkDeposit($request->all());
        // return $data ? $this->payment_status('success', $data?->payable_id??null, $walletDepositAction) 
        // : $this->payment_status('failed', null, $walletDepositAction) ; 
        if (!$data)
         return $this->payment_status('failed', null, $walletDepositAction) ; 
        else if($data->status == 'paid')
        return $this->payment_status('success', $data?->payable_id ??null, $walletDepositAction);
            else
        return $this->payment_status('failed', $data?->payable_id ??null, $walletDepositAction);
    }

    public function payment_status($status,$id, WalletDepositAction $walletDepositAction)
    {
        if(isset($id))
        {
            if ($status != 'success') {
                $walletDeposit = $walletDepositAction->update($id, ["confirmed" => 0,"status" => "failed"]);
                $user = User::find($walletDeposit->user_id);   
                    if ($user->hasWallet('UID_'.$user->id.'_Main')) {        
                        $wallet = $user->getWallet('UID_'.$user->id.'_Main');
                        $wallet->withdrawFloat($walletDeposit->amount);
                    }
                return ['status' => false , 'message' => "Error:paymentError"];                
            }else{
                $walletDeposit = $walletDepositAction->update($id, ["confirmed" => 1,"status" => "confirmed"]);
                return ;
            } 
    
        }else{
            $walletDeposit = $walletDepositAction->update($id, ["confirmed" => 0,"status" => "failed"]);

            $user = User::find($walletDeposit->user_id);   
                if ($user->hasWallet('UID_'.$user->id.'_Main')) {        
                    $wallet = $user->getWallet('UID_'.$user->id.'_Main');
                    $wallet->withdrawFloat($walletDeposit->amount);
                }
            return ['status' => false , 'message' => "Error:paymentError"];
        }
    }
}
