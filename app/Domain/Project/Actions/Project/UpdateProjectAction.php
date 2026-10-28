<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\Project\Repositories\ProjectRepository;

class UpdateProjectAction
{
    public function __construct(private ProjectRepository $projectRepository){}

    public function handle(Project $project, array $projectData): Project
    {
        $this->projectRepository->update($project->id, $projectData);
        // event(new ProjectUpdated($project));
        return $project->fresh();
    }
}
