<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Admin\Models\Admin;
use Domain\Project\Repositories\ProjectRepository;

class DeleteProjectAction
{
    public function __construct(protected ProjectRepository $projectRepository){}

    public function handle(int $projectId)
    {
        try{
            $project = $this->projectRepository->findById($projectId);
            if (!$project) {
                throw new \InvalidArgumentException("Project not found.");
            }
            $project->delete();
            return true;

        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to delete project: " . $e->getMessage());
        }

    }
}
