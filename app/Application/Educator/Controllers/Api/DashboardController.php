<?php

namespace App\Application\Educator\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\Educator\Resource\EducatorNotificationResource;
use Domain\Project\Models\Assignment;
use Domain\Project\Models\Project;
use Domain\Project\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use ApiResponseTraitV2;

    public function stats(Request $request): JsonResponse
    {
        $educator = $request->user();

        try {
            Log::info('Educator Dashboard Stats', [
                'educator_id' => $educator->id,
                'educator_name' => $educator->name
            ]);

            $data = $this->getDashboardData($educator);
            return $this->responseData($data, msg:'Dashboard statistics retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage(), [
                'educator_id' => $educator->id,
                'trace' => $e->getTraceAsString()
            ]);

            return $this->responseError(msg:'Failed to retrieve dashboard statistics: ' . $e->getMessage());
        }
    }

    public function notifications(Request $request): JsonResponse
    {
        $educatorId = (int) auth()->id();
        $perPage = (int) $request->get('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $query = NotificationNew::with('admin')
            ->where('educator_id', $educatorId)
            ->latest();

        if ($request->boolean('unread_only')) {
            $query->where('is_read', false);
        }

        $items = $query->paginate($perPage);
        $unreadCount = NotificationNew::where('educator_id', $educatorId)
            ->where('is_read', false)
            ->count();

        return $this->responseData([
            'notifications' => EducatorNotificationResource::collection($items),
            'unread_count' => $unreadCount,
        ], 200, 'Notifications retrieved successfully');
    }

    public function markNotificationRead(Request $request, NotificationNew $notification): JsonResponse
    {
        if ((int) $notification->educator_id !== (int) auth()->id()) {
            return $this->responseError([], 403, 'Unauthorized.');
        }

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return $this->responseData([
            'notification' => EducatorNotificationResource::make($notification->load('admin')),
        ], 200, 'Notification marked as read');
    }

    public function markAllNotificationsRead(Request $request): JsonResponse
    {
        NotificationNew::where('educator_id', (int) auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->responseData([
            'unread_count' => 0,
        ], 200, 'All notifications marked as read');
    }

    private function getDashboardData($educator): array
    {
        return [
            'stats' => $this->getBasicStats($educator),
            'recent_projects' => $this->getRecentProjects($educator),
            'pending_opportunities' => $this->getPendingOpportunities(),
            'recent_earnings' => $this->getRecentEarnings($educator),
            'active_tasks_list' => $this->getActiveTasksList($educator),
            'overdue_tasks_list' => $this->getOverdueTasksList($educator),
            'recent_assignments' => $this->getRecentAssignments($educator),
            'upcoming_deadlines' => $this->getUpcomingDeadlines($educator),
            'last_bids' => $this->getLastBids($educator),
            'notifications' => $this->getNotifications($educator),
        ];
    }

    private function getBasicStats($educator): array
    {
        return [
            'active_projects' => $this->getActiveProjectsCount($educator),
            'active_tasks' => $this->getActiveTasksCount($educator),
            'overdue_tasks' => $this->getOverdueTasksCount($educator),
            'completed_tasks' => $this->getCompletedTasksCount($educator),
            'pending_invitations' => $this->getPendingAssignmentsCount($educator),
            'total_earnings' => $this->getTotalEarnings($educator),
            'success_rate' => $this->calculateSuccessRate($educator),
            'average_rating' => (float) ($educator->rating ?? 0),
        ];
    }

    private function getActiveProjectsCount($educator): int
    {
        return Project::whereHas('tasks.assignments', function ($query) use ($educator) {
            $query->where('assigned_to', $educator->id)
                ->where('status', 'accepted')
                ->whereNull('deleted_at');
        })
        ->active()
        ->whereNull('deleted_at')
        ->count();
    }

    private function getActiveTasksCount($educator): int
    {
        return Task::assignedToEducator($educator->id)
            ->active()
            ->count();
    }

    private function getOverdueTasksCount($educator): int
    {
        return Task::assignedToEducator($educator->id)
            ->overdue()
            ->count();
    }


    private function getCompletedTasksCount($educator): int
    {
        return Task::assignedToEducator($educator->id)
            ->completed()
            ->count();
    }

    private function getPendingAssignmentsCount($educator): int
    {
        return Assignment::where('assigned_to', $educator->id)
            ->where('status', 'pending')
            ->whereNull('deleted_at')
            ->count();
    }

    private function getTotalEarnings($educator): float
    {
        return (float) Assignment::where('assigned_to', $educator->id)
            ->where('status', 'accepted')
            ->whereNull('deleted_at')
            ->sum('amount');
    }

    private function calculateSuccessRate($educator): float
    {
        $completedTasks = $this->getCompletedTasksCount($educator);
        $totalAssignedTasks = Task::assignedToEducator($educator->id)->count();

        if ($totalAssignedTasks === 0) {
            return 0.0;
        }

        return round(($completedTasks / $totalAssignedTasks) * 100, 2);
    }

    private function getRecentProjects($educator)
    {
        return Project::whereHas('tasks.assignments', function ($query) use ($educator) {
            $query->where('assigned_to', $educator->id)
                ->where('status', 'accepted')
                ->whereNull('deleted_at');
        })
        ->with(['tasks' => function ($query) use ($educator) {
            $query->assignedToEducator($educator->id)
                ->select(['id','uuid', 'project_id', 'title', 'status', 'deadline']);
        }])
        ->latest()
        ->limit(5)
        ->get(['id', 'uuid','title', 'description', 'deadline', 'is_published', 'status', 'created_at'])
        ->map(function ($project) {
            return [
                'id' => $project->uuid,
                'title' => $project->title,
                'description' => $project->description,
                'deadline' => $project->deadline?->format('Y-m-d'),
                'is_published' => $project->is_published,
                'status' => $project->status->value,
                'tasks_count' => $project->tasks->count(),
                'created_at' => $project->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    private function getPendingOpportunities()
    {
        return Project::where('is_published', true)
            ->whereHas('tasks', function ($query) {
                $query->where('bidding_status', 'OPEN')
                    ->whereNull('deleted_at');
            })
            ->withCount(['tasks as open_tasks_count' => function ($query) {
                $query->where('bidding_status', 'OPEN')
                    ->whereNull('deleted_at');
            }])
            ->latest()
            ->limit(5)
            ->get(['id','uuid', 'title', 'description', 'budget', 'deadline'])
            ->map(function ($project) {
                return [
                    'id' => $project->uuid,
                    'title' => $project->title,
                    'description' => $project->description,
                    'budget' => $project->budget,
                    'deadline' => $project->deadline?->format('Y-m-d'),
                    'open_tasks_count' => $project->open_tasks_count,
                ];
            });
    }

    public function getLastBids($educator)
    {
            $bids = $educator->bids->whereNull('deleted_at')->take(5)->map(function ($bid) {
                return [
                    'id' => $bid->uuid,
                    'project_title' => $bid?->project->title ?? 'N/A',
                    'tasks' => $bid->bidTasks->map(function ($bidTask) {
                        return [
                            'task_title' => $bidTask?->task ? $bidTask->task->title : 'N/A',
                            'amount' => $bidTask->amount,
                            'status' => $bidTask->status->value,
                        ];
                    }),
                    'totalAmount' => $bid->total_amount,
                    'created_at' => $bid->created_at?->format('Y-m-d H:i:s'),
                ];
            });


            return $bids;

    }

    private function getRecentEarnings($educator)
    {
        return Task::assignedToEducator($educator->id)
            ->completed()
            ->with(['acceptedAssignment' => function ($query) {
                $query->select(['id','uuid', 'task_id', 'amount', 'accepted_at']);
            }])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get(['id','uuid', 'title', 'status', 'updated_at'])
            ->map(function ($task) {
                return [
                    'id' => $task->uuid,
                    'title' => $task->title,
                    'status' => $task->status->value,
                    'completed_at' => $task->updated_at->format('Y-m-d H:i:s'),
                    'amount' => $task->acceptedAssignment->amount ?? 0,
                    'accepted_at' => $task->acceptedAssignment->accepted_at?->format('Y-m-d H:i:s'),
                ];
            });
    }

    private function getActiveTasksList($educator)
    {
        return Task::assignedToEducator($educator->id)
            ->active()
            ->with(['project' => function ($query) {
                $query->select(['id','uuid', 'title']);
            }])
            ->select(['id','uuid', 'title', 'deadline', 'status', 'project_id', 'description'])
            ->orderBy('deadline')
            ->limit(10)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->uuid,
                    'title' => $task->title,
                    'description' => $task->description,
                    'deadline' => $task->deadline?->format('Y-m-d'),
                    'status' => $task->status->value,
                    'project_title' => $task->project->title ?? 'N/A',
                    'days_remaining' => $task->deadline ? now()->diffInDays($task->deadline, false) : null,
                ];
            });
    }

    private function getOverdueTasksList($educator)
    {
        return Task::assignedToEducator($educator->id)
            ->overdue()
            ->with(['project' => function ($query) {
                $query->select(['id','uuid', 'title']);
            }])
            ->select(['id','uuid', 'title', 'deadline', 'status', 'project_id', 'description'])
            ->orderBy('deadline')
            ->limit(10)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->uuid,
                    'title' => $task->title,
                    'description' => $task->description,
                    'deadline' => $task->deadline?->format('Y-m-d'),
                    'status' => $task->status->value,
                    'project_title' => $task->project->title ?? 'N/A',
                    'days_overdue' => $task->deadline ? abs(now()->diffInDays($task->deadline, false)) : null,
                ];
            });
    }


    private function getRecentAssignments($educator)
    {
        return Assignment::where('assigned_to', $educator->id)
            // cancelled = withdrawn by the admin, so it is not a relationship
            // the specialist has any more
            ->where('status', '!=', AssignmentStatus::CANCELLED)
            ->with(['task' => function ($query) {
                $query->select(['id','uuid', 'title', 'project_id'])
                    ->with(['project' => function ($q) {
                        $q->select(['id','uuid', 'title']);
                    }]);
            }])
            ->whereNull('deleted_at')
            ->latest('assigned_at')
            ->limit(5)
            ->get(['id','uuid','task_id', 'status', 'amount', 'assigned_at', 'assigned_type', 'accepted_at'])
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->uuid,
                    'task_title' => $assignment->task->title ?? 'N/A',
                    'project_title' => $assignment->task->project->title ?? 'N/A',
                    'status' => $assignment->status->value,
                    'amount' => $assignment->amount,
                    'assigned_at' => $assignment->assigned_at?->format('Y-m-d H:i:s'),
                    'accepted_at' => $assignment->accepted_at?->format('Y-m-d H:i:s'),
                    'assigned_type' => $assignment->assigned_type->value,
                ];
            });
    }

    private function getUpcomingDeadlines($educator)
    {
        return Task::assignedToEducator($educator->id)
            ->active()
            ->where('deadline', '>=', now()->toDateString())
            ->with(['project' => function ($query) {
                $query->select(['id', 'title']);
            }])
            ->select(['id', 'title', 'deadline', 'status', 'project_id', 'description'])
            ->orderBy('deadline')
            ->limit(5)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->uuid,
                    'title' => $task->title,
                    'description' => $task->description,
                    'deadline' => $task->deadline?->format('Y-m-d'),
                    'status' => $task->status->value,
                    'project_title' => $task->project->title ?? 'N/A',
                    'days_remaining' => $task->deadline ? now()->diffInDays($task->deadline, false) : null,
                ];
            });
    }

    private function getNotifications($educator): array
    {
        $overdueCount = $this->getOverdueTasksCount($educator);
        $pendingInvitations = $this->getPendingAssignmentsCount($educator);
        $upcomingDeadlines = Task::assignedToEducator($educator->id)
            ->active()
            ->where('deadline', '<=', now()->addDays(3))
            ->where('deadline', '>=', now())
            ->count();

        $notifications = [];

        if ($overdueCount > 0) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Overdue Tasks',
                'message' => "You have {$overdueCount} overdue task(s) that need attention",
                'action' => '/tasks?filter=overdue',
                'icon' => 'alert-triangle',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
        }

        if ($pendingInvitations > 0) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Pending Invitations',
                'message' => "You have {$pendingInvitations} pending invitation(s) to review",
                'action' => '/assignments?status=pending',
                'icon' => 'mail',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
        }

        if ($upcomingDeadlines > 0) {
            $notifications[] = [
                'type' => 'success',
                'title' => 'Upcoming Deadlines',
                'message' => "You have {$upcomingDeadlines} task(s) due in the next 3 days",
                'action' => '/tasks?filter=upcoming',
                'icon' => 'calendar',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
        }

        return $notifications;
    }
}
