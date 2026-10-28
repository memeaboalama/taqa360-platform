<?php

namespace App\Domain\User\Actions\Address;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class FetchUserAddressAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(User $user,array $data = [])
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->userRepository->fetch_user_addresses_paginated($user , $per_page);
        }

        return $this->userRepository->fetch_user_addresses($user);
    }
}
