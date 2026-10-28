<?php

namespace App\Domain\User\Actions\Address;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class DeleteUserAddressAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param int $address_id
     * @return int|null
     */
    public function handle(User $user , int $address_id): ?int
    {
        return $this->userRepository->delete_user_addresses($user,$address_id);
    }
}
