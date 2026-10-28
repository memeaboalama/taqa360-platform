<?php

namespace App\Domain\User\Actions\Address;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserAddressRepository;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class ShowUserAddressAction
{
    private UserAddressRepository $userAddressRepository;

    public function __construct(UserAddressRepository $userAddressRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
    }

    public function handle(int $Id)
    {
        return $this->userAddressRepository->findByCols(cols: ['id'=>$Id,'user_id'=>auth()->id()],with:["city","distract"]);
    }
}
