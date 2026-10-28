<?php

namespace App\Support\Services\User\Profile;

use App\Support\Enum\FileTypesEnum;
use App\Domain\User\Models\User;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserSettingRepository;
use App\Support\Services\ImagesServices\ImageLocal;

class UpdateProfileService
{
    private UserRepository $userRepository;
    private  $user;
    private ImageLocal $imageLocal;
    private UserSettingRepository $userSettingRepository;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository,UserSettingRepository $userSettingRepository, ImageLocal $imageLocal)
    {
        $this->userRepository = $userRepository;
        $this->imageLocal = $imageLocal;
        $this->userSettingRepository = $userSettingRepository;
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
        $this->user = $this->userRepository->findById($user_id);

        if (isset($request['image'])) $this->updateImage($request['image'], $user_id);

        if (isset($request['language_id'])) $this->updateLanguage($request);

        $this->user = $this->userRepository->update($this->user,$request);


        if( in_array('phone',array_keys($this->user->getChanges())) )
        {
            $this->user = $this->userRepository->update($this->user,['phone_verified_at'=>null]);
        }

        if( in_array('email',array_keys($this->user->getChanges())) )
        {
            $this->user = $this->userRepository->update($this->user,['email_verified_at'=>null]);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->user;
    }

    /**
     * @param $image
     * @param int $user_id
     * @return void
     */
    public function updateImage( $image, int $user_id): void
    {
        $old_image = $this->user->profileImage()->first();

        $imageData = $this->imageLocal->storeWebpImage(img: $image, path: User::PROFILE_PATH, type: FileTypesEnum::PROFILE, oldPath: $old_image?->fullPath, disk: 'digitalocean');

        $old_image?->delete();

        $this->user = $this->userRepository->updateImage($user_id, $imageData);
    }

    /**
     * @param array $request
     * @return void
     */
    public function updateLanguage( array $request): void
    {
        $this->user = $this->user->userSetting
            ? $this->userSettingRepository->update($this->user->userSetting?->id, $request)?->user
            : $this->userSettingRepository->createForUser($this->user, $request)?->user;
    }
}
