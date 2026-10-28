<?php

namespace App\Application\Api\Controllers\User\Restaurant;

use App\Domain\MarketPlace\Actions\Api\Menu\FetchMenuAction;
use App\Domain\MarketPlace\resources\Menu\MenuResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function fetch(FetchMenuAction $fetchMenuAction): JsonResponse
    {
        $data = $fetchMenuAction->handle();
        return $this->responseData([
            'menus' => MenuResource::collection($data)
        ]);
    }
}
