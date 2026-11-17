<?php

namespace Tests\Feature;

use App\Application\Admin\Controllers\Api\NotificationController;
use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\User\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminNotificationsApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        Schema::create('notification_news', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 120);
            $table->string('body', 500);
            $table->string('channel');
            $table->string('device_type');
            $table->unsignedBigInteger('admin_id');
            $table->string('delivery_status', 32)->default('unknown');
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('notification_news');

        parent::tearDown();
    }

    public function test_authorized_admin_receives_paginated_notifications_with_audience_and_delivery_status(): void
    {
        DB::table('notification_news')->insert([
            'title' => 'Service update',
            'body' => 'A notice for users.',
            'channel' => 'broadcast',
            'device_type' => User::class,
            'admin_id' => 1,
            'delivery_status' => NotificationNew::DELIVERY_SENT,
            'sent_count' => 3,
            'failed_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = Request::create('/api/v1/admin/notifications?page=1&per_page=10', 'GET');
        $request->setUserResolver(fn () => (object) ['type' => 'admin']);

        $response = app(NotificationController::class)->index($request)->getData(true);

        $notification = $response['data']['notifications'][0];
        $this->assertSame('Service update', $notification['title']);
        $this->assertSame('A notice for users.', $notification['message']);
        $this->assertSame('user', $notification['audience']);
        $this->assertSame('success', $notification['type']);
        $this->assertSame(NotificationNew::DELIVERY_SENT, $notification['delivery_status']);
        $this->assertSame(1, $response['pagination']['meta']['page']['total']);
    }

    public function test_employee_without_notification_permission_is_forbidden(): void
    {
        $request = Request::create('/api/v1/admin/notifications', 'GET');
        $request->setUserResolver(fn () => (object) ['type' => 'employee']);

        $response = app(NotificationController::class)->index($request);

        $this->assertSame(403, $response->getStatusCode());
    }
}
