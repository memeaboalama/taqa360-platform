<?php

namespace App\Application\Api\Controllers\User\Wallet;


use App\Application\Api\Requests\Coupon\ApplyPromocodeRequest;
use App\Application\Api\Requests\WalletCoupon\ApplyWalletCouponRequest;
use App\Domain\MarketPlace\Actions\Api\Coupon\ApplyPromocodeAction;

use App\Domain\MarketPlace\Actions\Api\Wallet\ApplyWalletCouponAction;
use App\Domain\MarketPlace\resources\Cart\CartResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;


class WalletCouponController extends Controller
{
    use ApiResponseTrait;

    public function applyWalletCoupon(ApplyWalletCouponRequest $request, ApplyWalletCouponAction $applyWalletCouponAction): JsonResponse
    {

        $res = $applyWalletCouponAction->apply($request->validated());

        // if (!$cart["status"]) return $this->responseError([], 400, "Error:Coupon Not Found");

        return  $this->responseData( $res,200,$res["message"],$res["status"]);

    }
}
