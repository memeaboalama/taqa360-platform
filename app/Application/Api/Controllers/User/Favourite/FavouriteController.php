<?php

namespace App\Application\Api\Controllers\User\Favourite;

use App\Application\Api\Requests\Favourite\AddToFavouriteRequest;
use App\Application\Api\Requests\Favourite\RemoveFromFavouriteRequest;
use App\Domain\MarketPlace\Actions\Api\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Api\Favourite\AddFavouriteAction;
use App\Domain\MarketPlace\Actions\Api\Favourite\FetchFavouriteAction;
use App\Domain\MarketPlace\Actions\Api\Favourite\RemoveFavouriteAction;
use App\Domain\MarketPlace\resources\Favourite\FavouriteResource;
use App\Domain\MarketPlace\resources\Favourite\FavouriteResourceV2;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class FavouriteController extends Controller
{
    use ApiResponseTrait;

    public function index(FetchFavouriteAction $favouriteAction): JsonResponse
    {
        $favourites = $favouriteAction->handle();
        if (!$favourites) return $this->responseData([]);
        return $this->responseData(['favourites' => FavouriteResource::collection($favourites)]);
    }
    public function index_V2(FetchFavouriteAction $favouriteAction, FetchBranchAction $fetchBranchAction): JsonResponse
    {
        if (!auth('sanctum')->check())
            $this->responseData([]);
        $favourites = $favouriteAction->handle();
        if (!$favourites) return $this->responseData([]);
        if (request()->hasHeader('x-latitude') && request()->hasHeader('x-longitude')) {

            app()->instance('long', request()->header('x-longitude') ?? false);
            app()->instance('lat', request()->header('x-latitude') ?? false);
            // Log::channel("daily")->info("user id: ".$auth_id."  request x-latitude: ".request()->header('x-latitude') .", x-longitude: ".$request->header('x-longitude'));
            // Log::info("request x-latitude: ".$request->header('x-latitude') .", x-longitude: ".$request->header('x-longitude'));
        }
        $data = [
            // 'longitude'=>'43.910906967316',//resolve('long'),
            'longitude' => resolve('long'),
            // 'latitude'=>'26.378001636067594',//resolve('lat'),
            'latitude' => resolve('lat'),

        ];
        $me = $fetchBranchAction->handleFavourite($data);
        // return $this->responseData([
        //     'merchant' => MerchantResource::collection($data)
        // ]);

        return $this->responseData(['favourites' => FavouriteResourceV2::collection($me)]);
        // return $this->responseData(['favourites' => FavouriteResourceV2::collection($favourites)]);
    }
    public function add(AddToFavouriteRequest $request, AddFavouriteAction $addFavouriteAction): JsonResponse
    {
        $fav = $addFavouriteAction->add($request->validated());
        return $this->responseData(['favourite' => new FavouriteResource($fav)]);
    }

    public function remove(RemoveFromFavouriteRequest $request, RemoveFavouriteAction $removeFavouriteAction): JsonResponse
    {
        $fav = $removeFavouriteAction->remove($request->validated());
        return $this->responseData([]);
    }
}
