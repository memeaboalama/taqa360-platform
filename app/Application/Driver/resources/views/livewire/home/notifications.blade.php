<div wire:poll.10s>
    @include('shared.notification-dropdown', [
        'notifications' => $notifications,
        'notificationVariant' => 'dashboard',
        'includeNotificationModal' => false,
        'includeNotificationStyles' => true,
    ])
</div>
