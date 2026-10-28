<?php

namespace Domain\Educator\Actions\Auth;

use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterEducatorAction
{
    protected EducatorRepository $educatorRepository;
    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(array $data): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = (string) \Illuminate\Support\Str::uuid();
        $educator = $this->educatorRepository->store($data);
        // Auth::guard('educator')->login($educator);
        return $educator;
    }
}
