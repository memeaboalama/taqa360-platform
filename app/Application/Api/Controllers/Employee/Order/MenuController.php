<?php

namespace App\Application\Api\Controllers\Employee\Order;

use App\Application\Api\Requests\Employee\Menu\ChangeItemStatusRequest;
use App\Domain\MarketPlace\Actions\Api\Item\ChangeStatusAction;
use App\Domain\MarketPlace\Actions\Api\Item\FetchItemsAction;
use App\Domain\MarketPlace\Actions\Api\Menu\FetchMenuAction;
use App\Domain\MarketPlace\resources\Menu\ItemResource;
use App\Domain\MarketPlace\resources\Menu\MenuResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use ApiResponseTrait;

    public function getCategories(FetchMenuAction $fetchMenuAction): \Illuminate\Http\JsonResponse
    {
        $data = $fetchMenuAction->getByBranchEmp(auth()->guard('employee-api')->user());
        return $this->responseData([
            'menu' => new MenuResource($data)
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

    public function changeItemStatus(ChangeItemStatusRequest $request, ChangeStatusAction $changeStatusAction): \Illuminate\Http\JsonResponse
    {
        $item = $changeStatusAction->handle($request->validated());
        return $this->responseData([
            'item' => new ItemResource($item)
        ]);
    }
}
