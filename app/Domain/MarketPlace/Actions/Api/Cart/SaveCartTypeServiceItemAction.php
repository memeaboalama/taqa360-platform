<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\CartItem;
use App\Domain\MarketPlace\Repositories\Cart\CartRepository;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class SaveCartTypeServiceItemAction
{
    private CartRepository $cartRepository;
    private ItemRepository $itemRepository;

    public function __construct(CartRepository $cartRepository, ItemRepository $itemRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->itemRepository = $itemRepository;
    }

    public function addItem(array $data)
    {
        DB::beginTransaction();
        $cart = auth()->user()?->cart;
        if ($cart && ($cart?->merchant_id != $data['merchant_id'] || $cart?->merchant_branch_id != $data['merchant_branch_id'] || $cart?->service_type_id != $data['service_type_id']))
            return ["status" => false, "message" => "Error:" . __('admin::home.YoucanonlyorderfromonerestaurantWithOneService.'), "data" => null, "code" => 400];

        try {


            auth()->user()->cart()
                ->updateOrCreate([
                    'service_type_id' => $data['service_type_id'],
                    'merchant_id' => $data['merchant_id'],
                    'merchant_branch_id' => $data["merchant_branch_id"]

                ], ['updated_at' => now()]);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            Log::error("create cart item service types: " . json_encode($data) . "");
            // Log::error($exception,"create cart item service types: " . json_encode($cart)."");
        }
        DB::commit();
        return  $cart;
    }
}
