<?php

namespace Tests\Feature;

use App\Application\Admin\Controllers\Api\Edu\ServiceController;
use App\Application\Educational\Controllers\ServiceRequestWebController;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Support\Services\ImagesServices\ImageService;
use Domain\AppBase\Models\Edu\Service;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class ServiceRequestServicesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'service_options_test', 'database.connections.service_options_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ]]);
        DB::purge('service_options_test');
        (require app_path('Domain/AppBase/Migrations/2025_07_09_012226_create_services_table.php'))->up();
        Route::post('/_test/service-request', [ServiceRequestWebController::class, 'store']);
    }

    private function service(string $status = 'active'): Service
    {
        return Service::create(['name' => ['ar' => 'خدمة تجريبية', 'en' => 'Test service'], 'status' => $status]);
    }

    public function test_storefront_can_load_service_options_without_admin_authentication(): void
    {
        $service = $this->service();
        $this->getJson(route('service-requests.services'))
            ->assertOk()
            ->assertJsonPath('data.services.0.id', (string) $service->uuid);
    }

    public function test_options_include_all_active_services_and_reflect_admin_changes(): void
    {
        $active = $this->service();
        $inactive = $this->service('inactive');
        $deleted = $this->service();
        $deleted->delete();
        // The dropdown must include services beyond the admin API's default page size.
        for ($i = 0; $i < 16; $i++) {
            $this->service();
        }
        $response = app(ServiceController::class)->options()->getData(true);
        $this->assertCount(17, $response['data']['services']);
        $ids = array_column($response['data']['services'], 'id');
        $this->assertContains((string) $active->uuid, $ids);
        $this->assertNotContains((string) $inactive->uuid, $ids);
        $this->assertNotContains((string) $deleted->uuid, $ids);

        $active->update(['name' => ['ar' => 'اسم جديد', 'en' => 'Updated service']]);
        app()->setLocale('en');
        $response = app(ServiceController::class)->options()->getData(true);
        $this->assertSame('Updated service', $response['data']['services'][0]['name']);
    }

    public function test_submission_resolves_selected_service_uuid_to_database_id(): void
    {
        $service = $this->service();
        $this->mock(CreateEducationalRequestAction::class, function ($mock) use ($service) {
            $mock->shouldReceive('handle')->once()->withArgs(fn ($data) =>
                $data['service_id'] === $service->id && $data['type'] === 'general'
            )->andReturn(new EducationalRequest());
        });
        $this->postJson('/_test/service-request', [
            'title' => 'Test request', 'description' => 'Request description', 'service_id' => $service->uuid,
        ])->assertOk()->assertJsonPath('success', true);
    }

    public function test_submission_rejects_inactive_deleted_and_unknown_services(): void
    {
        $inactive = $this->service('inactive');
        $deleted = $this->service();
        $deleted->delete();
        $this->mock(CreateEducationalRequestAction::class)->shouldNotReceive('handle');
        foreach ([$inactive->uuid, $deleted->uuid, '00000000-0000-4000-8000-000000000000', null] as $uuid) {
            $this->postJson('/_test/service-request', [
                'title' => 'Test request', 'description' => 'Request description', 'service_id' => $uuid,
            ])->assertUnprocessable()->assertJsonValidationErrors('service_id');
        }
    }

    public function test_creation_preserves_service_and_legacy_request_type(): void
    {
        $service = $this->service();
        $repository = Mockery::mock(EducationalRequestRepository::class);
        $repository->shouldReceive('store')->once()->withArgs(fn ($data) =>
            $data['service_id'] === $service->id && $data['type'] === 'general'
        )->andReturn(new EducationalRequest());
        $action = new CreateEducationalRequestAction($repository, Mockery::mock(ImageService::class));
        $action->handle(['title' => 'Test', 'description' => 'Test request', 'user_id' => 1, 'service_id' => $service->id]);

        $request = new EducationalRequest(['type' => EducationalRequestType::GENERAL]);
        $request->setRelation('service', $service);
        $this->assertSame($service->name, $request->service_type_label);
        $request->setRelation('service', null);
        $this->assertSame(EducationalRequestType::GENERAL->trans(), $request->service_type_label);
    }

    public function test_service_migration_preserves_existing_requests_and_stores_service_reference(): void
    {
        Schema::create('educational_requests', fn (Blueprint $table) => $table->id());
        DB::table('educational_requests')->insert(['id' => 1]);
        $migration = require app_path('Domain/EducationalRequest/Migrations/2026_11_12_000001_add_service_id_to_educational_requests_table.php');
        $migration->up();
        $this->assertTrue(Schema::hasColumn('educational_requests', 'service_id'));
        $this->assertNull(DB::table('educational_requests')->where('id', 1)->value('service_id'));
        $service = $this->service();
        DB::table('educational_requests')->where('id', 1)->update(['service_id' => $service->id]);
        $this->assertSame($service->id, DB::table('educational_requests')->where('id', 1)->value('service_id'));
    }
}
