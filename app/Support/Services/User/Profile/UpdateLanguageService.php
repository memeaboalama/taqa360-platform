<?php

namespace App\Support\Services\User\Profile;

use App\Repositories\User\UserSettingRepository;

class UpdateLanguageService
{
    private UserSettingRepository $userSettingRepository;
    private  $user;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(UserSettingRepository $userRepository)
    {
        $this->userSettingRepository = $userRepository;
    }

    /**
     * Execute the service.
     *
     * @param int $user_id
     * @param array $request
     * @return UpdateLanguageService
     */
    public function handle(int $user_id ,array $request): static
    {
        $this->user = $this->userSettingRepository->update($user_id,$request)?->user;

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
