<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Model;

use App\Domain\AppBase\Repositories\ModelRepository;

class DestroyModelAction
{
    protected ModelRepository $modelRepository;


    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }


    public function handle(int $id): bool
    {
        return $this->modelRepository->destroy($id);
    }
}
