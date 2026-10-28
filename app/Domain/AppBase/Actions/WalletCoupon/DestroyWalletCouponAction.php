<?php

namespace App\Domain\AppBase\Actions\WalletCoupon;

use App\Domain\AppBase\Repositories\WalletCouponRepository;
use App\Support\Enum\StatusEnum;

class DestroyWalletCouponAction
{
    protected WalletCouponRepository $walletCouponRepository;


    public function __construct(WalletCouponRepository $walletCouponRepository)
    {
        $this->walletCouponRepository = $walletCouponRepository;
    }


    public function handle(int $id): bool
    {
        return $this->walletCouponRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::INACTIVE->value]);
    }
}
