<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Domain\AppBase\Repositories\CityRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCitiesAction
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        $relations = isset($data['with']) ? [$data['with'] . ":id,title,city_id"] : [];

        if(isset($data['paginated']))
        {
            $per_page = max(1, (int) ($data['per_page'] ?? 10));
            $page = isset($data['page']) ? max(1, (int) $data['page']) : null;
            $pageName = $data['page_name'] ?? 'page';
            $query = $this->cityRepository->builder(
                cols: ['id', 'title'],
                relations: $relations,
                condition: ['status' => StatusEnum::ACTIVE]
            );

            $search = trim((string) ($data['search'] ?? ''));
            if ($search !== '') {
                $locales = array_unique([app()->getLocale(), 'ar', 'en']);
                $query->where(function ($query) use ($locales, $search) {
                    foreach ($locales as $locale) {
                        $query->orWhere('title->' . $locale, 'like', '%' . $search . '%');
                    }
                });
            }

            return $query->paginate($per_page, ['*'], $pageName, $page);
        }
        return $this->cityRepository->all(cols: ['id','title'],relations:$relations, condition: ['status'=>StatusEnum::ACTIVE]);
    }
}
