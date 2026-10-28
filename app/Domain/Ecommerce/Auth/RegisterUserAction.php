<?php

namespace App\Domain\Ecommerce\Auth;

use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    protected UserRepository $UserRepository;
    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function handle(array $data): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = (string) \Illuminate\Support\Str::uuid();
        $educator = $this->UserRepository->store($data);
        // Auth::guard('educator')->login($educator);
        return $educator;
    }
}
