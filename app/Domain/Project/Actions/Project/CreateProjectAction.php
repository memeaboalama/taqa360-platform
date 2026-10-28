<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Admin\Models\Admin;
use Domain\Project\Repositories\ProjectRepository;

class CreateProjectAction
{
    public function __construct(protected ProjectRepository $projectRepository){}

    public function handle(array $projectData): Project
    {
        $projectData['created_by'] = 1;

        $educationalRequest = isset($projectData['educational_request_id']) && $projectData['educational_request_id']
            ? EducationalRequest::where('uuid',$projectData['educational_request_id'])->firstOrFail()
            : null;

        if ($educationalRequest) {
            $projectData['educational_request_id'] = $educationalRequest->id;
        }

        $project = $this->projectRepository->newProject($projectData);

        // event(new ProjectCreated($project));

        return $project;
    }
}
