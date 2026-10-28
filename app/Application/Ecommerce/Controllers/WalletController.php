<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\MarketPlace\Actions\Api\Wallet\ApplyWalletCouponAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletDepositAction;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    protected WalletDepositAction $walletDepositAction;
    protected ApplyWalletCouponAction $applyWalletCouponAction;

    public function __construct(
        WalletDepositAction $walletDepositAction,
        ApplyWalletCouponAction $applyWalletCouponAction
    ) {
        $this->walletDepositAction = $walletDepositAction;
        $this->applyWalletCouponAction = $applyWalletCouponAction;
    }

    public function index()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->latest()->paginate(10);

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account.wallet._index', compact('user', 'transactions'));
        }

        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'wallet',
            'user' => $user,
            'transactions' => $transactions,
        ]);
    }

    public function deposit()
    {
        $user = auth()->user();
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account.wallet._deposit');
        }

        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'wallet_deposit',
            'user' => $user,
        ]);
    }

    public function processDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $data = $request->only('amount');

        try {
            DB::beginTransaction();

            $walletDeposit = $this->walletDepositAction->create($data, $user);

            DB::commit();

            return redirect()->route('marketplace:wallet.payment.iframe', [
                'lang' => app()->getLocale(),
                'token' => $walletDeposit->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', __('ecommerce::ecommerce.something_went_wrong'));
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'walletCoupon' => 'required|string|exists:wallet_coupons,code',
        ]);

        try {
            $result = $this->applyWalletCouponAction->apply($request->only('walletCoupon'));

            if (!$result['status']) {
                $message = $result['message'];
                if(Str::contains($message, 'PromoCodeIsNotValid')) {
                    $message = __('ecommerce::ecommerce.coupon_invalid');
                } elseif(Str::contains($message, 'Thiscodehasbeenusedbefore')) {
                    $message = __('ecommerce::ecommerce.coupon_already_used');
                } else {
                    $message = str_replace('Error:', '', $message);
                }

                return back()->with('error', $message);
            }

            return redirect()->route('account.wallet.index')->with('success', $result['message']);

        } catch (\Exception $e) {
            Log::error($e);
            return back()->with('error', __('ecommerce::ecommerce.something_went_wrong'));
        }
    }

    public function withdraw()
    {
        $user = auth()->user();
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account.wallet._withdraw');
        }

        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'wallet_withdraw',
            'user' => $user,
        ]);
    }

    public function processWithdraw(Request $request)
    {
        $isAjax = $request->wantsJson() || $request->ajax();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'iban' => 'required|string',
        ], [
            'amount.required' => __('ecommerce::ecommerce.amount_required'),
            'amount.numeric' => __('ecommerce::ecommerce.amount_must_be_number'),
            'amount.min' => __('ecommerce::ecommerce.amount_min'),
            'bank_name.required' => __('ecommerce::ecommerce.bank_name_required'),
            'account_number.required' => __('ecommerce::ecommerce.account_number_required'),
            'iban.required' => __('ecommerce::ecommerce.iban_required'),
        ]);

        $user = auth()->user();

        // The user's real balance lives in their Main wallet (UID_{id}_Main), same as deposits
        $userWalletSlug = 'UID_' . $user->id . '_Main';

        if (!$user->hasWallet($userWalletSlug) || (float) $user->balance < (float) $request->amount) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => ['amount' => [__('ecommerce::ecommerce.withdraw_insufficient_funds')]],
                ], 422);
            }
            return back()->with('error', __('ecommerce::ecommerce.withdraw_insufficient_funds'));
        }

        try {
            DB::beginTransaction();

            $mainWallet = $user->getWallet($userWalletSlug);
            $mainWallet->withdrawFloat((float) $request->amount, [
                'type' => 'withdraw_request',
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'iban' => $request->iban,
                'status' => 'pending'
            ]);

            DB::commit();

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => __('ecommerce::ecommerce.withdraw_request_submitted'),
                    'redirect_url' => route('account.wallet.index'),
                ]);
            }

            return redirect()->route('account.wallet.index')->with('success', __('ecommerce::ecommerce.withdraw_request_submitted'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => __('ecommerce::ecommerce.something_went_wrong'),
                ], 500);
            }
            return back()->with('error', __('ecommerce::ecommerce.something_went_wrong'));
        }
    }
}
