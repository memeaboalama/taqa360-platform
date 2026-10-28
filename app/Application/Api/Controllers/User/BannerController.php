<?php

namespace App\Application\Api\Controllers\User;

use App\Domain\Admin\Actions\Dashboard\Banner\FetchBannerAction;
use App\Domain\AppBase\Resources\BannerResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class BannerController  extends Controller
{
    use ApiResponseTrait;
    public function fetch(FetchBannerAction $fetchBannerAction): JsonResponse
    {
        $data = $fetchBannerAction->handle();
        return $this->responseData([
            'banners' => BannerResource::collection($data)
        ]);
    }
}
