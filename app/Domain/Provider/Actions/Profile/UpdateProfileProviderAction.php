<?php

namespace App\Domain\Provider\Actions\Profile;


use App\Domain\AppBase\Actions\Image\SendImageToDB;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\Provider\Models\Provider;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateProfileProviderAction
{
    private ProviderRepository $providerRepository;
    private $imageAction;

    /**
     * @param ProviderRepository $providerRepository
     */
    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
        $this->imageAction = new SendImageToDB();
    }


    public function handle(array $data): ?\Illuminate\Contracts\Auth\Authenticatable
    {

        $user = auth()->user();
        $this->providerRepository->update($user->id, $data);
        if (isset($data["image"])) {

            $this->imageAction->handle($user, $data["image"], "upload/provider", FileTypesEnum::PROFILE);

        }

        return $user;
    }
}
