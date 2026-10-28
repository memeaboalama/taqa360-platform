<?php

namespace App\Domain\Ecommerce\Actions\Cart;

use App\Domain\Ecommerce\Repositories\Cart\WebCartRepository;
use Illuminate\Support\Facades\Log;

class UpdateQtyAction
{
    public function __construct(protected WebCartRepository $webCartRepository){}

    public function handle(array $data): \Illuminate\Database\Eloquent\Model|array|null
    {
        Log::info('data ----  data: ' ,[$data]);
        $userId = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$userId) {
            return null;
        }
        ($cart = $this->webCartRepository->findByCols(['user_id' => $userId]));

        Log::info('Cart found: ' ,[$cart]);

        if ($cart) {
            $item = $cart->cartItems()->where(['item_id' => $data['item_id']])->first();
            Log::info('Item: ' ,[$item]);

            if ($item && isset($data['qty'])) {
                $product = \Domain\MarketPlace\Models\Item::query()->where('id', $data['item_id'])->first();
                $requestedQty = (int) $data['qty'];

                if ($product && !$product->isInStock()) {
                    return ['code' => 400, 'message' => __('ecommerce::ecommerce.product_out_of_stock')];
                }

                if ($product && $product->stock !== null && $requestedQty > (int) $product->stock) {
                    return [
                        'code' => 400,
                        'message' => __('ecommerce::ecommerce.remaining_stock', ['count' => (int) $product->stock]),
                    ];
                }

                $item->update([
                    'qty' => $requestedQty,
                ]);
            }
        }

        return $cart;
    }
}
