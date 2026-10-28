<?php
namespace Application\Educator\Livewire\Home\Tabs;

use App\Application\Project\Enums\AssignmentStatus;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Assignment;
use Livewire\Component;

class Overview extends Component
{
    public Educator $educator;
    public $currentRequests = [];
    public $recentReviews = [];

    public function mount(Educator $educator)
    {
        $this->educator = $educator;

        $this->currentRequests = Assignment::where('assigned_to', $this->educator->id)
            ->whereIn('status', [AssignmentStatus::ACCEPTED->value, AssignmentStatus::IN_PROGRESS->value])
            ->whereHas('task', function ($q) {
                $q->whereIn('status', ['pending', 'in_progress']);
            })
            ->with(['task.project.educationalRequest.requester'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Assignment $assignment) => $this->requestRow($assignment))
            ->values()
            ->toArray();

        $this->recentReviews = Assignment::where('assigned_to', $this->educator->id)
            ->where('status', AssignmentStatus::COMPLETED->value)
            ->with(['task.project.educationalRequest.requester'])
            ->latest('completed_at')
            ->limit(5)
            ->get()
            ->map(fn (Assignment $assignment) => $this->reviewRow($assignment))
            ->values()
            ->toArray();
    }

    private function requestRow(Assignment $assignment): array
    {
        $task = $assignment->task;
        $project = $task?->project;
        $request = $project?->educationalRequest;

        return [
            'id' => $assignment->id,
            'title' => $task?->title ?? $project?->title ?? '—',
            'client' => $request?->requester?->name ?? $project?->requester_name ?? '—',
            'deadline' => $task?->deadline?->toIso8601String() ?? now()->toIso8601String(),
            'status' => $this->mapStatus($assignment->status),
            'price' => number_format((float) $assignment->amount, 0) . ' ' . __('educator::home.currency_sar'),
        ];
    }

    private function reviewRow(Assignment $assignment): array
    {
        $task = $assignment->task;
        $project = $task?->project;
        $request = $project?->educationalRequest;

        return [
            'id' => $assignment->id,
            'client' => $request?->requester?->name ?? $project?->requester_name ?? '—',
            'rating' => (int) round((float) ($this->educator->rating ?? 5)),
            'comment' => $task?->description ?? $project?->description ?? '',
            'date' => ($assignment->completed_at ?? $assignment->updated_at)->toIso8601String(),
        ];
    }

    private function mapStatus(AssignmentStatus $status): string
    {
        return match ($status) {
            AssignmentStatus::PENDING,
            AssignmentStatus::ACCEPTED => 'new',
            AssignmentStatus::IN_PROGRESS => 'in_progress',
            AssignmentStatus::COMPLETED => 'completed',
            default => 'in_progress',
        };
    }

    public function render()
    {
        return view('educator::livewire.home.tabs.overview');
    }
}