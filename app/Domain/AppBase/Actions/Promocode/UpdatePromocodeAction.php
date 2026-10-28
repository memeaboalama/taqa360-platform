<?php

namespace App\Domain\AppBase\Actions\Promocode;

use App\Domain\AppBase\Repositories\PromocodeRepository;

class UpdatePromocodeAction
{
    protected PromocodeRepository $promocodeRepository;


    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->promocodeRepository->update($id, $data);
    }
}
