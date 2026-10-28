<?php
namespace Application\Educator\Livewire\Home\Tabs;

use App\Application\Project\Enums\AssignmentStatus;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Assignment;
use Livewire\Component;

class Reviews extends Component
{
    public Educator $educator;
    public $reviews = [];

    public function mount(Educator $educator)
    {
        $this->educator = $educator;

        $this->reviews = Assignment::where('assigned_to', $this->educator->id)
            ->where('status', AssignmentStatus::COMPLETED->value)
            ->with(['task.project.educationalRequest.requester'])
            ->latest('completed_at')
            ->get()
            ->map(fn (Assignment $assignment) => $this->reviewRow($assignment))
            ->values()
            ->toArray();
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

    public function render()
    {
        return view('educator::livewire.home.tabs.reviews');
    }
}