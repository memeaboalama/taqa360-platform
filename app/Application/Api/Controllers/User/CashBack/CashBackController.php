<?php

namespace App\Application\Api\Controllers\User\CashBack;

use App\Application\Api\Requests\CashBack\ApplyCashBackRequest;
use App\Domain\MarketPlace\Actions\Api\CashBack\ApplyCashBackAction;

use App\Domain\MarketPlace\resources\Cart\CartResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CashBackController extends Controller
{
    use ApiResponseTrait;

    public function applyCashBack(ApplyCashBackRequest $request, ApplyCashBackAction $applyCashBackAction): JsonResponse
    {

        $cart = $applyCashBackAction->apply($request->validated());

        if (!$cart["status"]) return $this->responseError([], 400, $cart["message"]);

        return $this->responseData(['cart' => new CartResource($cart["data"])]);

    }

    public function removeCashBack()
    {

        $cart = auth()->user()->cart;
        if ($cart) {

            $cart->update(['cash_back_name' => null, 'cash_back_id' => null]);

            return $this->responseData(['cart' => new CartResource($cart)]);
        }


    }
}
