<?php

namespace App\Domain\Provider\Actions\Service;

use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchProviderMainServiceAction
{


    private ProviderMainServiceRepository $providerMainServiceRepository;

    public function __construct(ProviderMainServiceRepository $providerMainServiceRepository)
    {
        $this->providerMainServiceRepository = $providerMainServiceRepository;
    }

    /**
     * @param array $data
     * @param int $provider_category_id
     * @return CursorPaginator|Collection
     */
    public function handle(array $data , int $provider_category_id): CursorPaginator|Collection
    {
        $cols = ['id','title','main_category_id','provider_category_id'];
        $relations = ['main_category:id,title','provider_category:id,title'];
        $conditions = ['provider_category_id' => $provider_category_id,'status' => StatusEnum::ACTIVE];
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->providerMainServiceRepository->cursorPaginate(cols: $cols , relations: $relations,condition: $conditions, paginate: $per_page);
        }
        return $this->providerMainServiceRepository->all(cols: $cols, relations: $relations, condition: $conditions);
    }
}
