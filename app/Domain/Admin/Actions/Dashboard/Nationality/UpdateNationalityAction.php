<?php

namespace App\Domain\Admin\Actions\Dashboard\Nationality;

use App\Domain\Admin\Repositories\NationalityRepository;

class UpdateNationalityAction
{
    protected NationalityRepository $nationalityRepository;


    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->nationalityRepository->update($id, $data);
    }

}

