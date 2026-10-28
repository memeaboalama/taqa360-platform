<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Model;

use App\Domain\AppBase\Repositories\ModelRepository;

class UpdateModelAction
{
    protected ModelRepository $modelRepository;


    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->modelRepository->update($id, $data);
    }
}
