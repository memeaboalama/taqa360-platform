<?php

namespace App\Application\Api\Controllers\v2\User\Merchant;

use App\Application\Api\Requests\V2\Merchant\Branch\FetchBranchRequest;
use App\Application\Api\Requests\Restaurant\ShowSingleRestaurantRequest;

use App\Domain\MarketPlace\Actions\Api\V2\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Api\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchItemsAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Actions\Api\Merchant\FetchSingleMerchantAction;
use App\Domain\MarketPlace\resources\Branch\BranchResource;
use App\Domain\MarketPlace\resources\Menu\ItemResource;
use App\Domain\MarketPlace\resources\Merchant\MerchantDetailsResource;
use App\Domain\MarketPlace\resources\Merchant\MerchantResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function fetch(FetchBranchRequest $request, FetchBranchAction $fetchBranchAction): JsonResponse
    {
        $data = $fetchBranchAction->handle($request->validated());
        if (isset($data["code"]) && $data["code"] == 400) return $this->errorResponse(400, $data["message"]);
        if (!$data) return $this->responseError([], 400, 'Oops! provide any address');
        return $this->responseData([
            'merchant' => MerchantResource::collection($data)
        ]);
    }
    public function fetchPackUp(FetchBranchRequest $request, FetchBranchAction $fetchBranchAction): JsonResponse
    {
        $data = $fetchBranchAction->handlePackUp($request->validated());
        if (isset($data["code"]) && $data["code"] == 400) return $this->errorResponse(400, $data["message"]);
        if (!$data) return $this->responseError([], 400, 'Oops! provide any address');

        return $this->responseData([
            'merchant' => MerchantResource::collection($data)
        ]);
    }

    public function show(int $merchant_id, FetchSingleMerchantAction $fetchSingleMerchantAction): JsonResponse
    {

        $merchant = $fetchSingleMerchantAction->handle( $merchant_id);
        if (!$merchant)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'merchant' => new MerchantDetailsResource($merchant)
        ]);
    }
    public function show_merchant_branch(int $merchant_id, int $branch_id, FetchSingleBranchAction $fetchSingleBranchAction): JsonResponse
    {

        $branch = $fetchSingleBranchAction->handle($branch_id, $merchant_id);
        if (!$branch)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'branch' => new BranchResource($branch)
        ]);
    }

    public function showItemsByCategory(int $category_id, Request $request, FetchItemsAction $fetchItemsAction): JsonResponse
    {
        $items = $fetchItemsAction->getByCategory($category_id, $request->all());
        if (!$items)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'items' => ItemResource::collection($items)
        ]);
    }

    public function showItem(int $item_id, FetchSingleItemAction $fetchSingleItemAction): JsonResponse
    {
        $item = $fetchSingleItemAction->handle($item_id);
        if (!$item)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'item' => new ItemResource($item)
        ]);
    }
}
