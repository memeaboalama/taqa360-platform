<?php

namespace App\Http\View\Composers;

use App\Domain\Notification\Actions\FetchNotificationFeedAction;
use App\Domain\User\Models\User;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationFeedComposer
{
    private const VIEWS = [
        'ecommerce::layout.header' => [
            'audience' => User::class,
            'guards' => ['web'],
        ],
        'ecommerce::partials.store-header' => [
            'audience' => User::class,
            'guards' => ['web'],
        ],
        'driver::layout.header' => [
            'audience' => Driver::class,
            'guards' => ['driver'],
        ],
        'marketplace::layout.header' => [
            'audience' => MerchantEmployee::class,
            'guards' => ['merchant', 'merchant_employee'],
        ],
    ];

    public function __construct(private FetchNotificationFeedAction $fetchNotifications) {}

    public function compose(View $view): void
    {
        $view->with('notificationFeed', collect());

        $settings = self::VIEWS[$view->getName()] ?? null;
        if ($settings === null) {
            return;
        }

        foreach ($settings['guards'] as $guard) {
            if (Auth::guard($guard)->check()) {
                $view->with('notificationFeed', $this->fetchNotifications->handle($settings['audience']));

                return;
            }
        }
    }
}
