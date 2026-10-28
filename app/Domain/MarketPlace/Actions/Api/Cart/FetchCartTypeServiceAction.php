<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Repositories\Cart\CartRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchCartTypeServiceAction
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function handle()
    {
        // return $this->cartRepository->fetch(cols: ['id','merchant_id','user_id', 'merchant_branch_id','promocode','promocode_id','service_type_id'], relations: ["itemPrices","merchantBranchTypeServices","branch"], conditions: ["user_id" => auth()->guard('user-api')->user()->id]);
        return $this->cartRepository->fetch(cols: ['id', 'merchant_id', 'user_id', 'merchant_branch_id', 'promocode', 'promocode_id', 'service_type_id', 'cash_back', 'cash_back_id'], relations: ["cartItems", "merchantBranchTypeServices", "branch"], conditions: ["user_id" => auth()->guard('user-api')->user()->id]);
    }
}
