<?php

namespace App\Domain\MarketPlace\Actions\Api\Favourite;

use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\Repositories\Favourite\FavouriteRepository;
use Illuminate\Support\Facades\DB;

class AddFavouriteAction
{
    private FavouriteRepository $favouriteRepository;

    public function __construct(FavouriteRepository $favouriteRepository)
    {
        $this->favouriteRepository = $favouriteRepository;
    }

    public function add(array $data)
    {
        DB::beginTransaction();
        $fav=Favorite::updateOrCreate([  'user_id'=>auth()->guard('user-api')->user()->id,
            'merchant_id'=>$data["merchant_id"]],[
           'merchant_branch_id'=>$data["merchant_branch_id"],
        ]);

        DB::commit();
        return $fav;
    }
}
