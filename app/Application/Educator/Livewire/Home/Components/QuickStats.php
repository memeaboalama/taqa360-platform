<?php
namespace Application\Educator\Livewire\Home\Components;

use App\Application\Project\Enums\AssignmentStatus;
use Domain\Educator\Models\Educator;
use Livewire\Component;

class QuickStats extends Component
{
    public Educator $educator;
    public int $earningsThisMonth = 0;
    public int $responseRate = 0;
    public int $completionRate = 0;

    public function mount(Educator $educator)
    {
        $this->educator = $educator;

        $assignments = $this->educator->assignments()
            ->whereIn('status', AssignmentStatus::values())
            ->get(['status', 'amount', 'completed_at', 'updated_at']);

        $total = $assignments->count();
        $completed = $assignments->where('status', AssignmentStatus::COMPLETED->value)->count();

        $this->earningsThisMonth = (int) $this->educator->assignments()
            ->where('status', AssignmentStatus::COMPLETED->value)
            ->where(function ($query) {
                $query->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->orWhereBetween('updated_at', [now()->startOfMonth(), now()->endOfMonth()]);
            })
            ->sum('amount');

        $this->responseRate = $total > 0
            ? (int) round($assignments->where('status', '!=', AssignmentStatus::PENDING->value)->count() / $total * 100)
            : 0;

        $this->completionRate = $total > 0
            ? (int) round($completed / $total * 100)
            : 0;
    }

    public function render()
    {
        return view('educator::livewire.home.components.quick-stats');
    }
}