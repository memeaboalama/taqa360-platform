<?php

namespace App\Domain\User\Actions\Car;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class DeleteUserCarAction
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
        return $this->userRepository->delete_user_cars($user,$address_id);
    }
}
