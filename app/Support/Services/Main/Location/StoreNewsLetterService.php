<?php

namespace App\Support\Services\Main\Location;

use App\Domain\Otp\Models\Country;
use App\Repositories\Main\CityRepository;
use App\Repositories\Main\CountryRepository;
use App\Repositories\Main\NewsLetterRepository;
use App\Repositories\Main\SocialAccountRepository;
use Illuminate\Support\Collection;

class StoreNewsLetterService
{

    private NewsLetterRepository $newsLetterRepository;
    private $email;

    /**
     * @param NewsLetterRepository $newsLetterRepository
     */
    public function __construct(NewsLetterRepository $newsLetterRepository)
    {
        $this->newsLetterRepository = $newsLetterRepository;
    }

    public function handle(array $data): static
    {
        $this->email = !$this->newsLetterRepository->findBy('email',$data['email'])
            ? $this->newsLetterRepository->create($data)
            : $data['email'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->email;
    }
}
