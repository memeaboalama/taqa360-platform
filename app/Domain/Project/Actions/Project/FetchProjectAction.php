<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Admin\Models\Admin;
use Domain\Project\Repositories\ProjectRepository;

class FetchProjectAction
{
    public function __construct(protected ProjectRepository $projectRepository){}

    public function handle(array $filters = [])
    {
        if(isset($filters['paginated']))
        {
            $per_page = isset($filters['per_page']) ? $filters['per_page'] : null;
            return $this->projectRepository->filterAndPaginate($filters, ['createdBy'], $per_page ?? 10);
        }


        return $this->projectRepository->filter($filters)->latest()->select('*')->get();
    }
}
