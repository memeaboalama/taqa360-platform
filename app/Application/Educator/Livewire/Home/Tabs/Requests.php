<?php
namespace Application\Educator\Livewire\Home\Tabs;

use App\Application\Project\Enums\AssignmentStatus;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Assignment;
use Livewire\Component;

class Requests extends Component
{
    public Educator $educator;
    public $requests = [];

    public function mount(Educator $educator)
    {
        $this->educator = $educator;

        $this->requests = Assignment::where('assigned_to', $this->educator->id)
            ->whereIn('status', [
                AssignmentStatus::ACCEPTED->value,
                AssignmentStatus::IN_PROGRESS->value,
                AssignmentStatus::COMPLETED->value,
            ])
            ->with(['task.project.educationalRequest.requester'])
            ->latest()
            ->get()
            ->map(fn (Assignment $assignment) => $this->requestRow($assignment))
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
        return view('educator::livewire.home.tabs.requests');
    }
}