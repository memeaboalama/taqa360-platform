<?php

namespace App\Application\Api\Controllers\User\Wallet;

use App\Application\Api\Requests\Wallet\WalletDepositRequest;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletDepositAction;
use App\Domain\User\Models\User;
use App\Domain\User\resources\WalletResource;
use App\Domain\User\resources\WalletTransactionsResource;
use App\Http\Controllers\Controller;
use App\Payment\Payment;
use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    use ApiResponseTrait;
    public PaymentAction $paymentAction;
    public WalletDepositAction $walletDepositAction;

    public function __construct(PaymentAction $paymentAction ,WalletDepositAction $walletDepositAction)
    {
        $this->paymentAction = $paymentAction;
        $this->walletDepositAction = $walletDepositAction;
    }


    public function fetch(Request $request)
    {
        $user = request()->user();
        return $this->responseData([
            'wallet' => new WalletResource($user),
        ], 200);
    }
    


    public function deposit(WalletDepositRequest $request ){

        $data = $request->validated();
        $user = $request->user();
        $response=[];
        try {
            DB::beginTransaction();
            $walletDeposit = $this->walletDepositAction->create($data , $user);
            if ($user->status?->value != "active") return $this->errorResponse(401, __('auth.employee_not_active'));
            $response = [
                'Deposit Request' => $walletDeposit,
            ];
            if ($data["channel"] == ChannelTypesEnum::MOBILE->value ) {
                $response["apple"] = $this->payWithMada($walletDeposit);
            }
            DB::commit();
        }catch (Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
        }
        return $this->responseData($response);

    }

    public function getWalletTransaction(Request $request){

        $user = request()->user();
        $walletTransaction =$user->transactions;
        return  $this->responseData(["walletTransaction" => WalletTransactionsResource::collection($walletTransaction)]);
    }

    private function payWithMada($walletDeposit)
    {
        return route('marketplace:wallet.payment.iframe', ['lang' => app()->getLocale(), 'token' => $walletDeposit->id]);
    }
}
