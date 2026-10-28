<?php

namespace App\Domain\MarketPlace\Actions\Api\Favourite;

use App\Domain\MarketPlace\Repositories\Favourite\FavouriteRepository;

class FetchFavouriteAction
{
    private FavouriteRepository $favouriteRepository;

    public function __construct(FavouriteRepository $favouriteRepository)
    {
        $this->favouriteRepository = $favouriteRepository;
    }

    public function handle()
    {
        return $this->favouriteRepository->fetch(cols: ['id','merchant_id',"merchant_branch_id",'user_id'], relations: ["merchant"], conditions: ["user_id" => auth()->guard('user-api')->user()->id]);
    }
}
