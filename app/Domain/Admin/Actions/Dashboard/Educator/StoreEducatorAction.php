<?php

namespace App\Domain\Admin\Actions\Dashboard\Educator;

use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Support\Facades\Hash;

class StoreEducatorAction
{
    protected EducatorRepository $educatorRepository;

    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = (string) \Illuminate\Support\Str::uuid();
        $data['accepted_terms'] = true;
        $data['is_accepted'] = true;
        $data['availability_status'] = $data['status'] === 'active' ? 'active' : 'inactive';
        return $this->educatorRepository->store($data);
    }
}
