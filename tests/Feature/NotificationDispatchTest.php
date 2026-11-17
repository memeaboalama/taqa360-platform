<?php

namespace Tests\Feature;

use App\Domain\Admin\Actions\Dashboard\Notification\SendNotificationAction;
use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\Notification\Actions\FetchNotificationFeedAction;
use App\Domain\Notification\Actions\SendBroadcastPushNotificationAction;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Domain\User\Models\User;
use Application\Admin\Livewire\Notification\Component\Send;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class NotificationDispatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        Schema::dropIfExists('notification_news');
        Schema::create('notification_news', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 120);
            $table->string('body', 500);
            $table->string('channel');
            $table->string('device_type');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('educator_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('delivery_status', 32)->default('unknown');
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamps();
        });

        Schema::create('device_tokens', function (Blueprint $table): void {
            $table->id();
            $table->text('token');
            $table->string('platform')->default('ios');
            $table->morphs('deviceable');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('notification_news');
        Schema::dropIfExists('device_tokens');

        parent::tearDown();
    }

    public function test_delivery_counts_determine_notification_status(): void
    {
        $push = Mockery::mock(SendBroadcastPushNotificationAction::class);
        $push->shouldReceive('handle')->once()->andReturn([
            'attempted' => 3,
            'sent' => 2,
            'failed' => 1,
        ]);

        $notification = (new SendNotificationAction($push))->handle($this->notificationData());

        $this->assertSame(NotificationNew::DELIVERY_PARTIAL, $notification->delivery_status);
        $this->assertSame(2, $notification->sent_count);
        $this->assertSame(1, $notification->failed_count);
    }

    public function test_admin_broadcast_accepts_500_character_body_and_rejects_longer_messages(): void
    {
        $admin = new Admin;
        $admin->setAttribute('id', 1);
        $admin->setAttribute('type', 'admin');
        $this->actingAs($admin, 'admin');

        $push = Mockery::mock(SendBroadcastPushNotificationAction::class);
        $push->shouldReceive('handle')->once()->andReturn([
            'attempted' => 1,
            'sent' => 1,
            'failed' => 0,
        ]);
        $this->app->instance(SendBroadcastPushNotificationAction::class, $push);

        Livewire::test(Send::class)
            ->set('title', 'Service update')
            ->set('body', str_repeat('a', 500))
            ->set('device_type', User::class)
            ->call('send')
            ->assertHasNoErrors();

        $this->assertSame(500, strlen(DB::table('notification_news')->value('body')));

        Livewire::test(Send::class)
            ->set('title', 'Service update')
            ->set('body', str_repeat('a', 501))
            ->set('device_type', User::class)
            ->call('send')
            ->assertHasErrors(['body' => 'max']);
    }

    public function test_notification_without_device_tokens_is_not_marked_as_sent(): void
    {
        $push = Mockery::mock(SendBroadcastPushNotificationAction::class);
        $push->shouldReceive('handle')->once()->andReturn([
            'attempted' => 0,
            'sent' => 0,
            'failed' => 0,
        ]);

        $notification = (new SendNotificationAction($push))->handle($this->notificationData());

        $this->assertSame(NotificationNew::DELIVERY_NO_RECIPIENTS, $notification->delivery_status);
    }

    public function test_broadcast_with_no_tokens_does_not_require_firebase_credentials(): void
    {
        config(['services.firebase.credentials' => null]);

        $delivery = (new SendBroadcastPushNotificationAction)->handle([
            'device_type' => User::class,
            'title' => 'Service update',
            'body' => 'An update for users.',
        ]);

        $this->assertSame([
            'attempted' => 0,
            'sent' => 0,
            'failed' => 0,
        ], $delivery);
    }

    public function test_successful_delivery_is_recorded_with_counts(): void
    {
        $push = Mockery::mock(SendBroadcastPushNotificationAction::class);
        $push->shouldReceive('handle')->once()->andReturn([
            'attempted' => 2,
            'sent' => 2,
            'failed' => 0,
        ]);

        $notification = (new SendNotificationAction($push))->handle($this->notificationData());

        $this->assertSame(NotificationNew::DELIVERY_SENT, $notification->delivery_status);
        $this->assertSame(2, $notification->sent_count);
        $this->assertSame(0, $notification->failed_count);
    }

    public function test_firebase_exception_is_recorded_as_failed_and_rethrown(): void
    {
        $push = Mockery::mock(SendBroadcastPushNotificationAction::class);
        $push->shouldReceive('handle')->once()->andThrow(new RuntimeException('Firebase unavailable.'));

        try {
            (new SendNotificationAction($push))->handle($this->notificationData());
            $this->fail('The Firebase exception should be rethrown.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Firebase unavailable.', $exception->getMessage());
        }

        $this->assertDatabaseHas('notification_news', [
            'title' => 'Service update',
            'delivery_status' => NotificationNew::DELIVERY_FAILED,
        ]);
    }

    public function test_feed_returns_broadcasts_for_its_audience_even_if_push_was_not_delivered(): void
    {
        $this->insertNotification(User::class, NotificationNew::DELIVERY_SENT, 'Customer notice');
        $this->insertNotification(User::class, NotificationNew::DELIVERY_NO_RECIPIENTS, 'Dashboard-only notice');
        $this->insertNotification(User::class, NotificationNew::DELIVERY_FAILED, 'Failed notice');
        $this->insertNotification(\Domain\Driver\Models\Driver::class, NotificationNew::DELIVERY_SENT, 'Driver notice');

        $notifications = app(FetchNotificationFeedAction::class)->handle(User::class);

        $this->assertSame(
            ['Failed notice', 'Dashboard-only notice', 'Customer notice'],
            $notifications->pluck('title')->all()
        );
    }

    public function test_device_token_refresh_keeps_other_devices_for_the_same_user(): void
    {
        $user = new User;
        $user->setAttribute('id', 42);
        $registerToken = new SendIDeviceTokenToDB;

        $registerToken->handle($user, ['device_token' => 'device-a', 'platform' => 'ios']);
        $registerToken->handle($user, ['device_token' => 'device-a', 'platform' => 'android']);
        $registerToken->handle($user, ['device_token' => 'device-b', 'platform' => 'android']);

        $this->assertDatabaseCount('device_tokens', 2);
        $this->assertDatabaseHas('device_tokens', [
            'token' => 'device-a',
            'platform' => 'android',
            'deviceable_id' => 42,
            'deviceable_type' => User::class,
        ]);
    }

    private function notificationData(): array
    {
        return [
            'title' => 'Service update',
            'body' => 'An update for users.',
            'device_type' => User::class,
            'admin_id' => 1,
        ];
    }

    private function insertNotification(string $audience, string $status, string $title): void
    {
        DB::table('notification_news')->insert([
            'title' => $title,
            'body' => 'Notification body',
            'channel' => 'mobile',
            'device_type' => $audience,
            'admin_id' => 1,
            'delivery_status' => $status,
            'sent_count' => $status === NotificationNew::DELIVERY_SENT ? 1 : 0,
            'failed_count' => $status === NotificationNew::DELIVERY_FAILED ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
