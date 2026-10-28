<?php

namespace App\Domain\Educator\Actions\Bids;

use App\Domain\Project\Exceptions\BidException;
use App\Support\Services\Main\FilesServices\FileService;
use Domain\Project\Models\Bid;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Project;
use App\Domain\Project\Repositories\BidRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class StoreBidAction
{
    public function __construct(
        protected BidRepository $bidRepository,
        protected ImageService $imageService
    ) {}

    public function handle(Educator $educator, array $data): Bid
    {
        return DB::transaction(function () use ($educator, $data) {
            $project = $this->validateProject($data['project_id']);
            $this->validateBidSubmission($educator, $project, $data['bid_tasks'] ?? []);

            return $this->createBid($educator, $project, $data);
        });
    }

    private function validateProject(string $projectUuid): Project
    {
        $project = Project::where('uuid', $projectUuid)->firstOrFail();

        throw_unless($project->is_published, BidException::class, 'Bids can only be submitted for public tenders.');

        return $project;
    }

    private function validateBidSubmission(Educator $educator, Project $project, array $newTasks): void
    {
        if (empty($newTasks)) return;

        $existingBid = $this->bidRepository->findByCols(['project_id' => $project->id,'educator_id' => $educator->id]);

        if ($existingBid) $this->checkForDuplicateTasks($existingBid, $newTasks);
    }

    private function checkForDuplicateTasks(Bid $existingBid, array $newTasks): void
    {
        $existingTaskUuids = $existingBid->bidTasks()
            ->with('task')
            ->get()
            ->pluck('task.uuid')
            ->filter()
            ->toArray();

        $newTaskUuids = collect($newTasks)->pluck('task_id')->toArray();

        throw_if(!empty(array_intersect($existingTaskUuids, $newTaskUuids)), BidException::class,'You have already submitted a bid for one or more of these tasks.');
    }

    private function createBid(Educator $educator, Project $project, array $data): Bid
    {
        $bid = $project->bids()->create([
            'educator_id' => $educator->id,
            'proposal' => $data['proposal'] ?? null,
            'delivery_days' => $data['delivery_days'],
            'links' => $data['links'] ?? [],
        ]);

        foreach ($data['links'] ?? [] as $link) {
            $bid->links()->create([
                'title' => $link['title'] ?? '',
                'url' => $link['url'] ?? '',
            ]);
        }


        if (isset($data['bid_tasks'])) {
            $this->bidRepository->storeBidTasks($bid, $data['bid_tasks']);
        }

        if (isset($data['attachments'])) {
            $this->handleAttachments($educator, $bid, $data['attachments']);
        }

        return $bid->load(['project', 'bidTasks.task', 'attachments','links']);
    }

    private function handleAttachments($educator, Bid $bid, array $attachments): void
    {
        $path = "bids/attachments";
        $disk = 'fileDisk';

        foreach ($attachments as $attachment) {
            if (isset($attachment['file']) && $attachment['file'] instanceof UploadedFile) {

                $fileDetails = $this->imageService->storeImage($attachment['file'], $path, "BIDS", $disk, 'webp');
                $fileData = array_merge([
                    'collection_name' => 'bid_attachments',
                    'title' => $attachment['title'] ?? null,
                    'uploader_type' => get_class($educator),
                    'uploader_id' => $educator?->id,
                ], $fileDetails);

                $bid = $this->bidRepository->storeAttachment($bid, $fileData);
            }
        }
    }
}
