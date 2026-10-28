<?php

namespace App\Domain\User\Actions\Car;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class StoreUserCarAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function handle(User $user, array $data = []): mixed
    {
        return $this->userRepository->store_user_cars($user,$data);
    }
}
