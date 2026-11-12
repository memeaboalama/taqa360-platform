<?php

namespace Tests\Feature;

use Domain\AppBase\Models\Edu\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminServiceManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'admin_services_test', 'database.connections.admin_services_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('admin_services_test');

        (require app_path('Domain/AppBase/Migrations/2025_07_09_012226_create_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_13_000001_add_scope_of_work_to_services_table.php'))->up();

        Schema::create('educator_services', function ($table) {
            $table->id();
            $table->foreignId('service_id')->nullable();
            $table->softDeletes();
        });
        Schema::create('educational_requests', function ($table) {
            $table->id();
            $table->foreignId('service_id')->nullable();
        });

        Route::middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class)->group(function () {
            Route::apiResource('/_test/admin/edu/services', \App\Application\Admin\Controllers\Api\Edu\ServiceController::class);
        });
        app()->setLocale('ar');
    }

    private function servicePayload(string $suffix = ''): array
    {
        return [
            'name' => ['ar' => "خدمة {$suffix}", 'en' => "Service {$suffix}"],
            'description' => ['ar' => "وصف مختصر {$suffix}", 'en' => "Short description {$suffix}"],
            'scope_of_work' => ['ar' => "نطاق العمل {$suffix}", 'en' => "Scope of work {$suffix}"],
        ];
    }

    public function test_service_fields_persist_through_create_update_and_reopen(): void
    {
        $created = $this->postJson('/_test/admin/edu/services', $this->servicePayload('أ'))
            ->assertCreated()
            ->assertJsonPath('data.service.description', 'وصف مختصر أ')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل أ');

        $serviceId = $created->json('data.service.id');

        $this->putJson("/_test/admin/edu/services/{$serviceId}", $this->servicePayload('ب'))
            ->assertOk()
            ->assertJsonPath('data.service.name', 'خدمة ب')
            ->assertJsonPath('data.service.description', 'وصف مختصر ب')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل ب');

        $this->getJson("/_test/admin/edu/services/{$serviceId}")
            ->assertOk()
            ->assertJsonPath('data.service.name', 'خدمة ب')
            ->assertJsonPath('data.service.description', 'وصف مختصر ب')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل ب');
    }

    public function test_only_service_name_is_required_when_creating_and_updating(): void
    {
        $payload = [
            'name' => ['ar' => 'خدمة', 'en' => 'Service'],
            'description' => ['ar' => '', 'en' => ''],
            'scope_of_work' => ['ar' => '', 'en' => ''],
        ];

        $this->postJson('/_test/admin/edu/services', $payload)
            ->assertCreated()
            ->assertJsonPath('data.service.name', 'خدمة');

        $service = Service::create($this->servicePayload('موجودة'));
        $this->putJson("/_test/admin/edu/services/{$service->uuid}", $payload)
            ->assertOk()
            ->assertJsonPath('data.service.name', 'خدمة');

        $this->postJson('/_test/admin/edu/services', ['description' => ['ar' => 'وصف']])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    public function test_services_index_returns_only_requested_page_and_pagination_metadata(): void
    {
        foreach (range(1, 11) as $index) {
            Service::create($this->servicePayload((string) $index));
        }

        $this->getJson('/_test/admin/edu/services?per_page=10&page=2')
            ->assertOk()
            ->assertJsonPath('pagination.meta.page.current', 2)
            ->assertJsonPath('pagination.meta.page.per', 10)
            ->assertJsonPath('pagination.meta.page.total', 11)
            ->assertJsonCount(1, 'data');

        $this->getJson('/_test/admin/edu/services?per_page=10&search=11')
            ->assertOk()
            ->assertJsonPath('pagination.meta.page.total', 1)
            ->assertJsonCount(1, 'data');
    }

    public function test_service_cannot_be_deleted_while_referenced(): void
    {
        foreach (['educator_services', 'educational_requests'] as $table) {
            $service = Service::create($this->servicePayload($table));
            DB::table($table)->insert(['service_id' => $service->id]);

            $this->deleteJson("/_test/admin/edu/services/{$service->uuid}")
                ->assertStatus(409)
                ->assertJsonPath('message', 'SERVICE_IN_USE');

            $this->assertDatabaseHas('services', ['id' => $service->id, 'deleted_at' => null]);
        }
    }

    public function test_unused_service_can_be_deleted(): void
    {
        $service = Service::create($this->servicePayload('غير مستخدمة'));

        $this->deleteJson("/_test/admin/edu/services/{$service->uuid}")
            ->assertOk();

        $this->assertSoftDeleted('services', ['id' => $service->id]);
    }
}
