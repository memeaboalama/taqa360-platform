<?php

namespace Application\Marketplace\Controllers;


use App\Application\Marketplace\requests\Order\SavePaymentRequest;
use App\Application\Marketplace\requests\Wallet\SaveWalletPaymentRequest;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletDepositAction;
use App\Domain\AppBase\Models\WalletTransaction;
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
use Illuminate\Support\Facades\DB;
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
        $publishableKey = trim((string) config('services.moyasar.key'));
        $secretKey = trim((string) config('services.moyasar.secret'));
        if (
            !\Illuminate\Support\Str::startsWith($publishableKey, ['pk_test_', 'pk_live_']) ||
            !\Illuminate\Support\Str::startsWith($secretKey, ['sk_test_', 'sk_live_'])
        ) {
            return redirect()->route('account.wallet.deposit')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_not_configured'));
        }

        $walletDeposit = $walletDepositAction->handle($token);
        if ($walletDeposit && (int) $walletDeposit->user_id === (int) auth()->id()) {
            return view('marketplace::pages.payment.walletDeposit', get_defined_vars());
        }

        return abort(404);
    }

    public function payment_save(SaveWalletPaymentRequest $request, WalletDepositAction $walletDepositAction, PaymentAction $paymentAction): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $walletDeposit = $walletDepositAction->handle($data["key"]);
        if (!$walletDeposit) {
            return response()->json(['message' => 'data not found'], 404);
        }

        try {
            $paymentAction->handle($data, $walletDeposit);
        } catch (\Throwable $e) {
            Log::error('Wallet payment could not be saved: ' . $e->getMessage());

            return response()->json([
                'message' => __('ecommerce::ecommerce.wallet_payment_save_failed'),
            ], 500);
        }

        return response()->json([], 201);
    }

    public function payment_check(Request $request, PaymentAction $paymentAction , WalletDepositAction $walletDepositAction)
    {

        Log::channel('daily')->info("=== payment_check started ===");
        Log::channel('daily')->info("Request data: " . json_encode($request->all()));

        try {
            $data = $paymentAction->checkDeposit($request->all());
        } catch (\Throwable $e) {
            Log::error('Wallet payment verification failed: ' . $e->getMessage());

            return redirect()->route('account.wallet.index')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_verification_failed'));
        }
        Log::channel('daily')->info("checkDeposit returned: " . ($data ? "payment found" : "no data"));

        if (!$data) {
            return redirect()->route('account.wallet.index')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_verification_failed'));
        }
        else if($data->status == 'paid') {
            DB::transaction(function () use ($data) {
                $walletDeposit = WalletTransaction::query()
                    ->whereKey($data->payable_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($walletDeposit->confirmed) {
                    return;
                }

                $user = User::findOrFail($walletDeposit->user_id);
                $userWallet = 'UID_' . $user->id . '_Main';
                $wallet = $user->hasWallet($userWallet)
                    ? $user->getWallet($userWallet)
                    : $user->createWallet([
                        'name' => str_replace('_', ':', $userWallet),
                        'description' => 'User Main Wallet',
                        'slug' => $userWallet,
                    ]);

                $wallet->depositFloat($walletDeposit->amount, [
                    'type' => 'deposit_request',
                    'status' => 'confirmed',
                ], true);

                $walletDeposit->update(['confirmed' => 1, 'status' => 'confirmed']);
            });

            return redirect()->route('account.wallet.index')
                ->with('success', __('ecommerce::ecommerce.wallet_payment_success'));
        }
        elseif ($data->status == 'failed') {
            return $this->payment_status('failed', $data->payable_id, $walletDepositAction);
        } else {
            return redirect()->route('account.wallet.index')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_pending'));
        }
    }

    public function payment_status($status,$id, WalletDepositAction $walletDepositAction)
    {
        Log::channel('daily')->info("=== payment_status started ===");
        Log::channel('daily')->info("Status: $status, ID: " . ($id ?? 'null'));

        if ($status === 'failed' && isset($id)) {
            $walletDepositAction->update($id, ["confirmed" => 0, "status" => "failed"]);
            return redirect()->route('account.wallet.index')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_failed'));
        }

        if (!isset($id) || $status === 'success') {
            return redirect()->route('account.wallet.index')
                ->with('error', __('ecommerce::ecommerce.wallet_payment_verification_failed'));
        }

        return redirect()->route('account.wallet.index')
            ->with('error', __('ecommerce::ecommerce.wallet_payment_pending'));
    }
}