<?php

namespace App\Domain\Educator\Actions;

use Domain\Educator\Repositories\EducatorRepository;


class FetchEducatorAction
{
    protected EducatorRepository $educatorRepository;

    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(array $filters = [])
    {
        if(isset($filters['paginated']))
        {
            $per_page = isset($filters['per_page']) ? $filters['per_page'] : null;
            return $this->educatorRepository->filterAndPaginate($filters, ['profile', 'academicMajors.approvedMajor'], $per_page?? 10);
        }

        return $this->educatorRepository->filter($filters)->latest()->select('*')->get();
    }
}
