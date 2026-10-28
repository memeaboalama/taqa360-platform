<?php

namespace App\Domain\User\Actions\Car;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class UpdateUserCarAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param array $data
     * @param int $address_id
     * @return mixed
     */
    public function handle(User $user, array $data , int $address_id): mixed
    {
        return $this->userRepository->update_user_cars($user,$data,$address_id);
    }
}
