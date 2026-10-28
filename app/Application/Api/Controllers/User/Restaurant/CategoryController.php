<?php

namespace App\Application\Api\Controllers\User\Restaurant;

use App\Application\Api\Requests\Restaurant\Category\FetchCategoryRequest;
use App\Domain\AppBase\Actions\Category\FetchMainCategoryAction;
use App\Domain\AppBase\Resources\MainCategoryResource;
use App\Domain\MarketPlace\Actions\Api\Category\FetchCategoryAction;
use App\Domain\MarketPlace\resources\Category\CategoryResource;
use App\Domain\Merchant\Actions\Category\FetchMerchantCategoryAction;
use App\Domain\Merchant\resources\MerchantCategoryResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller

{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function fetch(FetchCategoryRequest $request, FetchCategoryAction $fetchCategoryAction): JsonResponse
    {

        $data = $fetchCategoryAction->handle($request->validated());
        return $this->responseSucessData([
            'categories' => CategoryResource::collection($data)
        ]);
    }

    public function fetchMain(FetchCategoryRequest $request, FetchMainCategoryAction $fetchMainCategoryAction): JsonResponse
    {
        $data = $fetchMainCategoryAction->handle($request->validated());
        return $this->responseSucessData([
            'categories' => MainCategoryResource::collection($data)
        ]);
    }
}
