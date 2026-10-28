<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\Project\Repositories\ProjectRepository;

class ProjectChangeStatusAction
{
    public function __construct(private ProjectRepository $projectRepository){}

    public function handle(Project $project, array $projectData): Project
    {
        $this->projectRepository->update($project->id, ['status' => $projectData['status']]);
        // event(new ProjectUpdated($project));
        return $project->fresh();
    }
}
