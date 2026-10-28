<?php

namespace Domain\Educator\Actions\Auth;

use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectEducatorAction
{
    private EducatorRepository $educatorRepository;

    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function detectEducatorApi(array $data): Model|bool|null
    {
        if (!Auth::guard('educator')->attempt(request()->only('email', 'password'))) {
            return false;
        }

        return $this->educatorRepository->findByCols(cols: ['email' => $data["email"]],with:[]);
    }
}
