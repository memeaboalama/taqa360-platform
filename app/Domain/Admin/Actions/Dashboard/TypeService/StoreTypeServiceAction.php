<?php

namespace App\Domain\Admin\Actions\Dashboard\TypeService;

use App\Domain\MarketPlace\Repositories\TypeService\TypeServiceRepository;

class StoreTypeServiceAction
{
    protected TypeServiceRepository $type_service_repository;


    public function __construct(TypeServiceRepository $type_service_repository)
    {
        $this->type_service_repository = $type_service_repository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
       return  $this->type_service_repository->store($data['package_type_services']);
        
    }
}
