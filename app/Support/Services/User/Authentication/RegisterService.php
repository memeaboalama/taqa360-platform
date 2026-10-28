<?php

namespace App\Support\Services\User\Authentication;

use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Model;

class RegisterService
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $user_data
     * @return Model|null
     */
    public function handle(array $user_data): ?Model
    {
        $user_data['password'] = bcrypt($user_data['password']);

        return $this->userRepository->create($user_data);
    }
}
