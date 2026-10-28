<?php
namespace Application\Educator\Livewire\Home;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\AppBase\Models\NotificationNew;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public Educator $educator;
    public string $activeTab = 'overview';
    public array $stats = [];
    public $notifications;
    public int $unreadCount = 0;
    public $selectedNotification = null;

    protected $queryString = ['activeTab' => ['except' => 'overview', 'as' => 'tab']];

    public function mount()
    {
        $this->educator = Auth::guard('educator')->user()->load('profile');
        $this->loadNotifications();

        $activeStatuses = [
            AssignmentStatus::ACCEPTED->value,
            AssignmentStatus::IN_PROGRESS->value,
            AssignmentStatus::COMPLETED->value,
        ];

        $totalRequests = Assignment::where('assigned_to', $this->educator->id)
            ->whereIn('status', $activeStatuses)
            ->count();
        $completedRequests = Assignment::where('assigned_to', $this->educator->id)
            ->where('status', AssignmentStatus::COMPLETED->value)
            ->count();

        $this->stats = [
            ['title' => __('educator::home.stats.total_requests'), 'value' => $totalRequests, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => '#6366f1'],
            ['title' => __('educator::home.stats.completed_requests'), 'value' => $completedRequests, 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#10b981'],
            ['title' => __('educator::home.stats.pending_requests'), 'value' => max($totalRequests - $completedRequests, 0), 'icon' => 'M16.023 9.348h4.992v-.001a.75.75 0 010 1.5H11.25a.75.75 0 01-.75-.75V2.25a.75.75 0 011.5 0v5.571a.75.75 0 01-.75.75H16.023z', 'color' => '#f59e0b'],
            ['title' => __('educator::home.stats.overall_rating'), 'value' => number_format((float) $this->educator->rating, 1), 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'color' => '#8b5cf6'],
        ];
    }

    public function changeTab(string $tabName)
    {
        $this->activeTab = $tabName;
    }

    public function loadNotifications()
    {
        $this->notifications = NotificationNew::where('educator_id', $this->educator->id)
            ->latest()
            ->limit(30)
            ->get();
        $this->unreadCount = NotificationNew::where('educator_id', $this->educator->id)
            ->where('is_read', false)
            ->count();
    }

    public function markNotificationsRead()
    {
        NotificationNew::where('educator_id', $this->educator->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        $this->loadNotifications();
    }

    public function openNotification(int $notificationId)
    {
        $notification = NotificationNew::where('educator_id', $this->educator->id)
            ->find($notificationId);

        if ($notification) {
            if (! $notification->is_read) {
                $notification->update(['is_read' => true]);
                $this->loadNotifications();
            }
            $this->selectedNotification = $notification;
        }
    }

    public function closeNotification()
    {
        $this->selectedNotification = null;
    }

    public function render()
    {
        return view('educator::livewire.home.index');
    }
}