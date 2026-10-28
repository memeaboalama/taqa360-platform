<?php

namespace App\Support\Services\Main\Location;

use App\Domain\Otp\Models\Country;
use App\Repositories\Main\CityRepository;
use App\Repositories\Main\CountryRepository;
use App\Repositories\Main\SocialAccountRepository;
use Illuminate\Support\Collection;

class FetchSocialAccountService
{

    private SocialAccountRepository $socialAccountRepository;
    private $socialAccounts;

    /**
     * @param SocialAccountRepository $socialAccountRepository
     */
    public function __construct(SocialAccountRepository $socialAccountRepository)
    {
        $this->socialAccountRepository = $socialAccountRepository;
    }

    public function handle(): static
    {
        $this->socialAccounts = $this->socialAccountRepository
            ->query()
            ->activeOnly()
            ->ownBuilder(['id','title','url'],['file'])
            ->fetch();

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->socialAccounts;
    }
}
