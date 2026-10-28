<?php

namespace App\Application\Api\Controllers\User\Cart;

use App\Application\Api\Requests\Cart\AddCartTypeServiceItemRequest;
use App\Application\Api\Requests\Cart\AddItemRequest;
use App\Application\Api\Requests\Cart\ChangeCartServiceTypeRequest;
use App\Application\Api\Requests\Cart\RemoveItemRequest;
use App\Application\Api\Requests\Cart\UpdateQtyRequest;
use App\Application\Api\Requests\Cart\UpdateServiceTypeQtyRequest;
use App\Application\Api\Requests\Cart\UpdateServiceTypeRequest;
use App\Domain\MarketPlace\Actions\Api\Cart\ChangeCartTypeServiceAction;
use App\Domain\MarketPlace\Actions\Api\Cart\DeleteItemCartAction;
use App\Domain\MarketPlace\Actions\Api\Cart\DeleteItemCartTypeServiceAction;
use App\Domain\MarketPlace\Actions\Api\Cart\FetchCartAction;
use App\Domain\MarketPlace\Actions\Api\Cart\FetchCartTypeServiceAction;
use App\Domain\MarketPlace\Actions\Api\Cart\SaveCartTypeServiceItemAction;
use App\Domain\MarketPlace\Actions\Api\Cart\SaveItemCartAction;
use App\Domain\MarketPlace\Actions\Api\Cart\UpdateQtyAction;
use App\Domain\MarketPlace\Actions\Api\Cart\UpdateCartTypeServiceAction;
use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\resources\Cart\CartItemResource;
use App\Domain\MarketPlace\resources\Cart\CartResource;
use App\Domain\MarketPlace\resources\Cart\CartTypeServiceResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function index(FetchCartAction $cartAction): JsonResponse
    {
        $cart = $cartAction->handle();
        if (!$cart) return $this->responseData([]);
        return $this->responseData(['cart' => new CartResource($cart)]);
    }

    public function getItems(FetchCartAction $cartAction): JsonResponse
    {
        $cart = $cartAction->handle();
        if (!$cart) return $this->responseData([]);
        return $this->responseData(['cart' => CartItemResource::collection($cart->cartItems()->paginate(10))]);
    }

    public function addItem(AddItemRequest $request, SaveItemCartAction $saveItemCartAction): JsonResponse
    {
        $cart = $saveItemCartAction->addItem($request->all());
        if (isset($cart["code"]) == 400) return $this->errorResponse(400, $cart["message"]);

        return $this->responseData(['cart' => new CartResource(Cart::query()->where('user_id', auth()->id())->first())]);
    }

    public function removeItem(RemoveItemRequest $request, DeleteItemCartAction $deleteItemCartAction): JsonResponse
    {
        $cart = $deleteItemCartAction->removeV2($request->validated());
        if (!$cart) return $this->responseData([]);
        return $this->responseData(['cart' => new CartResource($cart)]);
    }

    public function clearItems(DeleteItemCartAction $deleteItemCartAction): JsonResponse
    {
        $cart = $deleteItemCartAction->clear();
        if (!$cart) return $this->responseData([]);
        return  $this->responseData([]);
    }

    public function updateQty(UpdateQtyRequest $request, UpdateQtyAction $updateQtyAction, DeleteItemCartAction $deleteItemCartAction): JsonResponse
    {
        if ($request->qty == 0) {
            $cart = $deleteItemCartAction->removeV2($request->validated());
            if (!$cart) return $this->responseData([]);
        } else {
            $cart = $updateQtyAction->handle($request->validated());
        }

        return  $request->user()->cart ?
            $this->responseData(['cart' => new CartResource($request->user()->cart)]) :
            $this->responseData([]);
    }
    public function updateCartTypeService(UpdateServiceTypeRequest $request, UpdateCartTypeServiceAction $updateQtyAction, DeleteItemCartAction $deleteItemCartAction): JsonResponse
    {

        $cart = $updateQtyAction->handle($request->validated());

        return  $request->user()->cart ?
            $this->responseData(['cart' => new CartResource($request->user()->cart)]) :
            $this->responseData([]);
    }
    /****
     * 
     * Cart Type Service
     */
    public function fetchCartTypeService(FetchCartTypeServiceAction $cartAction): JsonResponse
    {
        $cart = $cartAction->handle();
        if (!$cart) return $this->responseData([]);
        return $this->responseData(['cart' => new CartTypeServiceResource($cart)]);
    }
    public function changeCartTypeService(ChangeCartServiceTypeRequest $request, ChangeCartTypeServiceAction $updateQtyAction): JsonResponse
    {

        // Log::channel('console')->info("create cart service types: " . json_encode($request->all()));
        $cart = $updateQtyAction->handle($request->validated());

        return  $request->user()->cart ?
            $this->responseData(['cart' => new CartTypeServiceResource($request->user()->cart)]) :
            $this->responseData([]);
    }

    public function fetchCartTypeServiceItems(FetchCartTypeServiceAction $cartAction): JsonResponse
    {
        $cart = $cartAction->handle();
        if (!$cart) return $this->responseData([]);
        // return $this->responseData(['cart' => CartItemResource::collection($cart->itemPrices()->paginate(10))]);
        return $this->responseData(['cart' => CartItemResource::collection($cart->cartItems()->paginate(10))]);
    }


    public function addCartTypeServiceItem(AddCartTypeServiceItemRequest $request, SaveCartTypeServiceItemAction $saveItemCartAction): JsonResponse
    {
        Log::debug("create cart item service types: " . json_encode($request->all()) . "");
        $cart = $saveItemCartAction->addItem($request->all());
        if (isset($cart["code"]) == 400) return $this->errorResponse(400, $cart["message"]);

        return $this->responseData(['cart' => new CartTypeServiceResource(Cart::query()->where('user_id', auth()->id())->first())]);
    }

    public function removeCartTypeServiceItem(RemoveItemRequest $request, DeleteItemCartTypeServiceAction $deleteItemCartAction): JsonResponse
    {
        $cart = $deleteItemCartAction->remove($request->validated());
        if (!$cart)
            return $this->responseData([]);
        return $this->responseData(['cart' => new CartTypeServiceResource($cart)]);
        // return $this->responseData(['cart' => new CartTypeServiceResource($cart)]);
    }
    public function changeCartTypeServiceItemQty(UpdateServiceTypeQtyRequest $request, UpdateQtyAction $updateQtyAction, DeleteItemCartTypeServiceAction $deleteItemCartAction): JsonResponse
    {
        $data = $request->validated();
        if (isset($request->qty)) {
            if ($request->qty <= 0) {
                Log::debug("remove cart item service types: " . json_encode($data) . "");
                $cart = $deleteItemCartAction->remove($request->validated());
                // $cart = $deleteItemCartAction->remove($data);
                Log::debug("removed cart item service types: " . json_encode($data) . "");
                if (!$cart)
                    return $this->responseData([]);
                return $this->responseData(['cart' => new CartTypeServiceResource($cart)]);
            } else {
                // $cart = $updateQtyAction->handle($data);
                $cart = $updateQtyAction->handle($request->validated());
                if (!$cart)
                    return $this->responseData([]);
                return  $this->responseData(['cart' => new CartTypeServiceResource($cart)]);
            }
        }

        return  $request->user()->cart ?
            $this->responseData(['cart' => new CartTypeServiceResource($request->user()->cart)]) :
            $this->responseData([]);
    }

    public function clearCartTypeServiceItems(DeleteItemCartTypeServiceAction $deleteItemCartAction): JsonResponse
    {
        $cart = $deleteItemCartAction->clear();
        if (!$cart) return $this->responseData([]);
        return  $this->responseData([]);
    }
}
