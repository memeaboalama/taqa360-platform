<?php

namespace App\Domain\User\Actions\Profile;

use App\Domain\AppBase\Actions\Image\SendImageToDB;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateUserAction
{
    private UserRepository $userRepository;

    private $imageAction;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->imageAction = new SendImageToDB();

    }

    public function handle($user, array $data): User|Authenticatable|null
    {
        $user->update($data);
        if (isset($data["image"])) {
            $this->imageAction->handle($user,$data["image"],"upload/user", FileTypesEnum::PROFILE);

        }


        return $user;
    }
}
