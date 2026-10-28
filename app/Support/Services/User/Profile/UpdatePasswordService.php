<?php

namespace App\Support\Services\User\Profile;

use App\Repositories\User\UserRepository;

class UpdatePasswordService
{
    private UserRepository $userRepository;
    private  $user;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the service.
     *
     * @param int $user_id
     * @param array $request
     * @return UpdateProfileService
     */
    public function handle(int $user_id ,array $request): static
    {
        $request['password'] = bcrypt($request['new_password']);

        $this->user = $this->userRepository->update($user_id,$request);

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->user;
    }
}
