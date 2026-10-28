<?php

namespace App\Application\Api\Controllers\ElmApp\Merchant;

use App\Application\Api\Requests\ElmApp\Merchant\FetchMerchantsRequest;
use App\Application\Api\Requests\ElmApp\Merchant\Branch\FetchBranchRequest;
use App\Application\Api\Requests\ElmApp\Merchant\FetchOrdersReportRequest;
use App\Application\Api\Requests\ElmApp\Merchant\ShowSingleRestaurantRequest;

use App\Domain\ElmApp\Actions\Merchant\FetchOrdersReportAction;
use App\Domain\ElmApp\Actions\Merchant\FetchMerchantsAction;
use App\Domain\ElmApp\resources\OrderResource;
use App\Domain\MarketPlace\Actions\Api\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Api\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchItemsAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchSingleItemAction;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\MarketPlace\resources\Branch\BranchResource;
use App\Domain\MarketPlace\resources\ElmApp\MerchantResource;
use App\Domain\MarketPlace\resources\Menu\ItemResource;
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
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }
   
    public function getMerchants(FetchMerchantsRequest $request,FetchMerchantsAction $fetchMerchantsAction)
    {
        $data = $request->all();
        $merchants = $fetchMerchantsAction->handle($data);
        return $this->responseData([
            // 'merchant' => MerchantResource::collection($merchants)
            'merchant' => $merchants,
        ]);
    }
    
    public function show(int $merchant_id, int $branch_id, FetchSingleBranchAction $fetchSingleBranchAction): JsonResponse
    {

        $branch = $fetchSingleBranchAction->handle($branch_id, $merchant_id);
        if(!$branch)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'branch' => new BranchResource($branch)
        ]);
    }

    public function showItemsByCategory(int $category_id, Request $request, FetchItemsAction $fetchItemsAction): JsonResponse
    {
        $items = $fetchItemsAction->getByCategory($category_id, $request->all());
        if(!$items)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'items' => ItemResource::collection($items)
        ]);
    }

    public function showItem(int $item_id, FetchSingleItemAction $fetchSingleItemAction): JsonResponse
    {
        $item = $fetchSingleItemAction->handle($item_id);
        if(!$item)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'item' => new ItemResource($item)
        ]);
    }

    public function getOrdersReport(FetchOrdersReportRequest $request,FetchOrdersReportAction $fetchOrdersReportAction): JsonResponse
    {
        $data = $request->all();
        $orders = $fetchOrdersReportAction->handle($data);
        return $this->responseData([
        'orders' => OrderResource::collection($orders)
        ]);
    }

}
