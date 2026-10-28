<?php

namespace App\Providers;

use App\Domain\Ecommerce\Models\WebCart;
use App\Domain\Ecommerce\Observers\WebCartObserver;
use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Observers\CartObserver;
use App\Domain\MarketPlace\Observers\OrderObserver;
use App\Domain\Provider\Events\ProviderRegistered;
use App\Domain\User\Events\UserLogged;
use App\Domain\User\listeners\SetDefaultEmailAndPhone;
use App\Domain\User\listeners\SetLastLogin;
use App\Domain\User\Models\User;
use App\Domain\User\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SetDefaultEmailAndPhone::class,
            SetLastLogin::class,
        ],
        UserLogged::class => [
            SetLastLogin::class,
        ],
//        ProviderRegistered::class => [
//            SetDefaultEmailAndPhone::class,
//            SetLastLogin::class,
//        ],
//        ProviderUserLogged::class => [
//            SetLastLogin::class,
//        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Cart::observe(CartObserver::class);
        WebCart::observe(WebCartObserver::class);
        User::observe(UserObserver::class);
        MerchantOrder::observe(OrderObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
