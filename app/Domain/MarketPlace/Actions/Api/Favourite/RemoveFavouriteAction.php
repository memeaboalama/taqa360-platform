<?php

namespace App\Domain\MarketPlace\Actions\Api\Favourite;

use App\Domain\MarketPlace\Repositories\Favourite\FavouriteRepository;
use Illuminate\Support\Facades\DB;

class RemoveFavouriteAction
{
    private FavouriteRepository $favouriteRepository;

    public function __construct(FavouriteRepository $favouriteRepository)
    {
        $this->favouriteRepository = $favouriteRepository;
    }

    public function remove(array $data)
    {
        DB::beginTransaction();
        $fav = $this->favouriteRepository->findByCols(['id' => $data['fav_id']]);
        $fav->delete();
        DB::commit();
        return $fav;
    }
}
