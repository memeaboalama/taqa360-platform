<?php

namespace App\Application\Api\Controllers\User\Coupon;

use App\Application\Api\Requests\Coupon\ApplyPromocodeRequest;
use App\Domain\MarketPlace\Actions\Api\Coupon\ApplyPromocodeAction;

use App\Domain\MarketPlace\resources\Cart\CartResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    use ApiResponseTrait;

    public function applyPromocode(ApplyPromocodeRequest $request, ApplyPromocodeAction $applyPromocodeAction): JsonResponse
    {

        $cart = $applyPromocodeAction->apply($request->validated());

        if (!$cart["status"]) return $this->responseError([], 400, $cart["message"]);

        return $this->responseData(['cart' => new CartResource($cart["data"])]);

    }

    public function removePromocode()
    {

        $cart = auth()->user()->cart;
        if ($cart) {

            $cart->update(['promocode' => null, 'promocode_id' => null]);

            return $this->responseData(['cart' => new CartResource($cart)]);
        }


    }
}
