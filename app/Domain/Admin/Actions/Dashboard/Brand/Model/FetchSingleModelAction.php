<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Model;

use App\Domain\AppBase\Repositories\ModelRepository;

class FetchSingleModelAction
{
    protected ModelRepository $modelRepository;


    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->modelRepository->findByCols(['id' => $id]);
    }
}
