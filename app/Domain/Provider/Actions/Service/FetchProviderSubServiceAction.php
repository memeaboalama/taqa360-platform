<?php

namespace App\Domain\Provider\Actions\Service;

use App\Domain\Provider\Repositories\ProviderSubServiceRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchProviderSubServiceAction
{


    private ProviderSubServiceRepository $providerSubServiceRepository;

    public function __construct(ProviderSubServiceRepository $providerSubServiceRepository)
    {
        $this->providerSubServiceRepository = $providerSubServiceRepository;
    }

    /**
     * @param array $data
     * @param int $provider_main_service_id
     * @return CursorPaginator|Collection
     */
    public function handle(array $data , int $provider_main_service_id): CursorPaginator|Collection
    {
        $cols = [
            'id',
            'provider_main_service_id',
            'provider_category_id',
            'main_category_id',
            'title',
            'description',
            'price',
            'schedulable',
            'increasable',
            'strategy',
            'towing_domain',
            'towing_type_id',
            'towing_open_price',
            'towing_price_per_km',
            'towing_price_per_minute',
        ];
        $relations = ['provider_main_service:id,title','towing_type:id,title'];

        $conditions = ['provider_main_service_id' => $provider_main_service_id,'status' => StatusEnum::ACTIVE];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->providerSubServiceRepository->cursorPaginate(cols: $cols , relations: $relations,condition: $conditions, paginate: $per_page);
        }
        return $this->providerSubServiceRepository->all(cols: $cols, relations: $relations, condition: $conditions);
    }
}
