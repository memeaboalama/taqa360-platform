<?php

namespace Application\Driver\Livewire\Home;

use App\Domain\Notification\Actions\FetchNotificationFeedAction;
use App\Domain\Notification\Notifications\Driver\OrderAssigned;
use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        $driver = auth('driver')->user();
        abort_unless($driver, 403);

        $assignedOrderNotifications = $driver->notifications()
            ->where('type', OrderAssigned::class)
            ->latest()
            ->limit(5)
            ->get()
            ->map(static function ($notification) {
                return (object) [
                    'id' => $notification->id,
                    'title' => data_get($notification->data, 'title', ''),
                    'body' => data_get($notification->data, 'body', ''),
                    'created_at' => $notification->created_at,
                ];
            });

        $notifications = app(FetchNotificationFeedAction::class)
            ->handle(\Domain\Driver\Models\Driver::class)
            ->concat($assignedOrderNotifications)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('driver::livewire.home.notifications', compact('notifications'));
    }
}
