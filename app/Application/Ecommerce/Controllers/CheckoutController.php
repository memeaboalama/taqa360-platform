<?php

namespace App\Application\Ecommerce\Controllers;

use App\Application\Ecommerce\Requests\ApplyPromocodeRequest;
use App\Application\Ecommerce\Requests\PlaceWebOrderRequest;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\Ecommerce\Actions\Web\Order\CreateWebOrderAction;
use App\Domain\Ecommerce\Actions\Web\Coupon\ApplyWebPromocodeAction;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Http\Controllers\Controller;
use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Payment\Payment;
use App\Domain\User\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder;
use App\Support\Enum\StatusEnum;
use App\Domain\MarketPlace\Models\MerchantOrder;

class CheckoutController extends Controller
{
    private function withUnpolyNotification($redirect, $message, $type = 'success')
    {
        $payload = json_encode(['message' => $message, 'type' => $type == 'error' ? 'danger' : $type]);
        $headerVal = base64_encode($payload);

        // Return 204 No Content so Unpoly does not update the DOM, but headers are processed.
        return response()->noContent()->header('X-App-Notification', $headerVal);
    }

    private function translateOrderError(?string $message, $data = null): string
    {
        $raw = str_replace('Error:', '', (string) $message);

        $map = [
            'NoCartData' => __('ecommerce::ecommerce.cart_not_found'),
            'CartIsEmpty' => __('ecommerce::ecommerce.empty_cart'),
            'Branchisnotfoundpleaseemptycart!' => __('ecommerce::ecommerce.branch_not_found'),
            'Restaurantisclosedatthistime!' => __('ecommerce::ecommerce.branch_closed'),
            'minordervalueis' => __('ecommerce::ecommerce.min_order_value', ['value' => $data]),
            'thepromocodeisnotforthisrestaurant' => __('ecommerce::ecommerce.promo_code_not_for_merchant'),
            'thecashbackisnotforthisrestaurant' => __('ecommerce::ecommerce.cashback_not_for_merchant'),
            'On Store Order in Order Action' => __('ecommerce::ecommerce.order_creation_failed'),
        ];

        return $map[$raw] ?? $raw;
    }

    public PaymentAction $paymentAction;
    public CreateWebOrderAction $orderAction;
    public WalletOrderAction $walletOrderAction;
    private LogOrderHistoryAction $logOrderHistoryAction;

    public function __construct(
        CreateWebOrderAction $orderAction,
        PaymentAction $paymentAction,
        WalletOrderAction $walletOrderAction,
        LogOrderHistoryAction $logOrderHistoryAction
    ) {
        $this->paymentAction = $paymentAction;
        $this->orderAction = $orderAction;
        $this->walletOrderAction = $walletOrderAction;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('auth.login');
        }


        $cart = $user->webCart()->with('cartItems.item')->first();

        // if($cart) {
        //      Log::info('Checkout Debug: Cart found. ID: ' . $cart->id . ' Items: ' . $cart->cartItems->count());
        // } else {
        //      Log::info('Checkout Debug: Cart is NULL via relationship');
        // }

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('home');
        }

        $addresses = $user->addresses;
        $cities = City::where('status', 'active')->get();
        $countries = \App\Domain\AppBase\Models\Location\Country::where('status', 'active')->get();

        return view('ecommerce::pages.checkout.checkout', compact('user', 'cart', 'addresses', 'cities', 'countries'));
    }

    public function applyCoupon(ApplyPromocodeRequest $request)
    {
        try {
            $user = auth()->user();
            $cart = $user->webCart()->first();

            if (!$cart) {
                return redirect()->back()->withInput()->with('coupon_error', __('ecommerce::ecommerce.cart_not_found'));
            }

            $applyAction = app(ApplyWebPromocodeAction::class);
            $result = $applyAction->apply($request->validated(), $cart);

            if ($result['status']) {
                return redirect()->back()->with('coupon_success', __('ecommerce::ecommerce.coupon_applied'));
            } else {
                return redirect()->back()->withInput()->with('coupon_error', $result['message']);
            }

        } catch (Exception $e) {
            Log::error('Apply coupon error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('coupon_error', __('ecommerce::ecommerce.coupon_error'));
        }
    }

    public function removeCoupon()
    {
        try {
            $user = auth()->user();
            $cart = $user->webCart()->first();

            if ($cart) {
                $cart->update(['promocode' => null, 'promocode_id' => null]);
                return redirect()->back()->with('coupon_success', __('ecommerce::ecommerce.coupon_removed'));
            }

            return redirect()->back();

        } catch (Exception $e) {
            Log::error('Remove coupon error: ' . $e->getMessage());
            return redirect()->back()->with('coupon_error', __('ecommerce::ecommerce.coupon_error'));
        }
    }

    public function store(PlaceWebOrderRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // stock guard: never place an order for unavailable / insufficient-stock items
        $cartForStock = $user->webCart()->with('cartItems.item')->first();
        if ($cartForStock && $cartForStock->cartItems->isNotEmpty()) {
            foreach ($cartForStock->cartItems as $cartItem) {
                $product = $cartItem->item;
                if (!$product) {
                    return $this->withUnpolyNotification(redirect()->back(), __('ecommerce::ecommerce.product_not_available'), 'error');
                }
                if (!$product->isInStock()) {
                    return $this->withUnpolyNotification(redirect()->back(), __('ecommerce::ecommerce.product_out_of_stock'), 'error');
                }
                if ($product->stock !== null && (int) $cartItem->qty > (int) $product->stock) {
                    return $this->withUnpolyNotification(redirect()->back(), __('ecommerce::ecommerce.remaining_stock', ['count' => (int) $product->stock]), 'error');
                }
            }
        }

        $data['channel'] = ChannelTypesEnum::WEB->value;

        $isNewAddress = isset($data['address_id']) && $data['address_id'] === 'new';
        if (!$isNewAddress && isset($data['address_id'])) {
            $data['address'] = $user->addresses()->find($data['address_id']);
            if (!$data['address']) {
                return redirect()->back()->with('error', __('ecommerce::ecommerce.invalid_address'));
            }
        }

        // FIX: Ensure correct logic for wallet payment before order creation
        if ($data['payment_method'] === 'wallet') {
            // Validation: Check Balance BEFORE creating order
            $cart = $user->webCart;
            if ($cart) {
                $total = $cart->total_after_vat;
                Log::info("Pre-check wallet balance", ['user_id' => $user->id, 'balance' => $user->balance, 'required' => $total]);

                if ($user->balance < $total) {
                     return $this->withUnpolyNotification(redirect()->back(), __('ecommerce::ecommerce.insufficient_balance'), 'error');
                }
            }

            $data['wallet'] = 1;
        }

        try {
            DB::beginTransaction();

            if ($isNewAddress) {
                $newAddress = UserAddress::create([
                    'user_id' => $user->id,
                    'title' => $data['new_address_title'],
                    'country_id' => $data['new_address_country_id'],
                    'city_id' => $data['new_address_city_id'],
                    'district_id' => $data['new_address_district_id'] ?? null,
                    'address' => $data['new_address_address'],
                    'latitude' => $data['new_address_latitude'] ?? 0,
                    'longitude' => $data['new_address_longitude'] ?? 0,
                ]);

                $data['address_id'] = $newAddress->id;
                $data['address'] = $newAddress;
            }

            $order = $this->orderAction->handle($data, $user);

            if (!$order["status"]) {
                DB::rollBack();
                return $this->withUnpolyNotification(redirect()->back(), $this->translateOrderError($order["message"], $order["data"] ?? null), 'error');
            }

            $reason = 'User create web';
            $this->logOrderHistoryAction->handle(
                $order['data']->id,
                MerchantOrderHistoryStatuses::CREATE_ORDER->value,
                $reason,
                $user,
            );

            $paymentResponse = [];

            if ($order['data']->total_after_discount > 0) {
                $paymentResponse = $this->processPayment($data, $order, $user->webCart);
            } else {
                $paymentResponse = $this->walletOrCachPayment($data, $order, $user->webCart);
            }

            if (isset($paymentResponse['error']) && $paymentResponse['error']) {
                DB::rollBack();
                return $this->withUnpolyNotification(redirect()->back(), $paymentResponse['message'], 'error');
            }

            DB::commit();

            if (isset($paymentResponse['paymentUrl'])) {
                return redirect($paymentResponse['paymentUrl']);
            }

            return redirect()->route('checkout.success')->with('success', __('ecommerce::ecommerce.order_placed_successfully'));

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return $this->withUnpolyNotification(redirect()->back(), __('ecommerce::ecommerce.something_went_wrong'), 'error');
        }
    }

    private function processPayment($data, $order, $cart)
    {
        $response = [];

        if ($data["payment_method"] == PaymentMethod::COD->value) {
            return $this->walletOrCachPayment($data, $order, $cart);
        }

        if ($data["payment_method"] == 'wallet') {
            $user = auth()->user();
            $total = $cart->total_after_vat;
            Log::info("Processing wallet payment ", ['user_id' => $user->id, 'wallet_balance' => $user->balance, 'order_total' => $total]);

            if ($user->balance <  $total) {
                return ['error' => true, 'message' => __('ecommerce::ecommerce.insufficient_balance')];
            }

            $data['wallet'] = 1;
            return $this->walletOrCachPayment($data, $order, $cart);
        }

        if ($data["payment_method"] == PaymentMethod::MADA->value ||
            $data["payment_method"] == 'credit_card') {

            $response["paymentUrl"] = $this->payWithMada($order['data']);
        }

        return $response;
    }


    private function walletOrCachPayment($data, $order, $cart)
    {
        try {
            if (isset($data['wallet']) && $data['wallet'] == 1) {
                // API Logic sync
                $payment = $this->walletOrderAction->withdraw($order["data"], "Wallet Order Pay");
                if ($payment && !$payment["status"]) {
                    // Log::error(json_encode($payment)); // Optional logging
                    return ['error' => true, 'message' => $payment["message"]];
                }
            }

            // Notifications as per API
            $this->notifyEmployees($order["data"]);
            $this->notifyBranchManager($order["data"]);

            $order["data"]->update(['status' => MerchantOrderStatuses::PENDING]);
            $this->clearCart($cart);

             // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee))->afterResponse(); logic is inside notifyEmployees

            return ["success" => true, "paidFromWallet" => true];

        } catch (Exception $exception) {
            Log::error($exception);
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

    protected function notifyEmployees(MerchantOrder $order): void
    {
        $employees = $order->branch->employees()->where('status', StatusEnum::ACTIVE->value)->get();
        foreach ($employees as $employee) {
            NewOrderEvent::dispatch($employee, $order);
            dispatch_sync(new SendNotifyOrder($order, $employee));
        }
    }

    protected function notifyBranchManager(MerchantOrder $order): void
    {
        $manager = $order->branch->manager()->first();
        if ($manager) {
            NewOrderEvent::dispatch($manager, $order);
            dispatch_sync(new SendNotifyOrder($order, $manager));
        }
    }

    public function success()
    {
        return view('ecommerce::pages.checkout.success');
    }

    private function payWithMada($order)
    {
        return route('marketplace:order.payment.iframe', ['lang' => app()->getLocale(), 'token' => $order->id]);
    }

    private function clearCart($cart)
    {
        if($cart) {
            $cart->cartChoices()->delete();
            $cart->cartItems()->delete();
            $cart->delete();
        }
    }
}
