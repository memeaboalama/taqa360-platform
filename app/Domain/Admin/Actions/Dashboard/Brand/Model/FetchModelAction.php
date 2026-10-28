<?php

namespace App\Domain\Admin\Actions\Dashboard\Brand\Model;

use App\Domain\AppBase\Repositories\ModelRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchModelAction
{
    protected ModelRepository $modelRepository;


    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }


    public function handle(): Collection
    {
        return $this->modelRepository->all();
    }
}
