<?php

namespace App\Domain\Ecommerce\Actions\Cart;

use App\Domain\Ecommerce\Repositories\Cart\WebCartRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchCartAction
{
    public function __construct(protected WebCartRepository $webCartRepository){}

    public function handle()
    {
        $userId = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$userId) {
            return null;
        }

        return $this->webCartRepository->fetch(
            cols: ['id', 'user_id', 'promocode', 'promocode_id', 'service_type_id', 'cash_back', 'cash_back_id'],
            relations: ["cartItems.item"],
            conditions: ["user_id" => $userId]
        );
    }
}
