<?php

namespace App\Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectFile;
use Domain\Project\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Storage;

class DeleteAttachmentAction
{
    public function __construct(
        protected ProjectRepository $projectRepository
    ) {}

    public function handle(Project $project, ProjectFile $attachment): bool
    {
        $disk = 'fileDisk';

        if ($attachment->fileable->id !== $project->id) {
            throw new \Exception("This attachment does not belong to this project");
        }

        if (!empty($attachment->path) && Storage::disk($disk)->exists($attachment->path)) {
            Storage::disk($disk)->delete($attachment->path);
        }

        $this->projectRepository->deleteAttachment($attachment);

        // event(new ProjectUpdated($project));

        return true;
    }
}
