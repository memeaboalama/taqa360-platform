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
        (require app_path('Domain/AppBase/Migrations/2026_11_22_000002_add_request_config_to_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_22_000004_add_service_details_to_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_23_000001_remove_display_details_from_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_23_000002_add_service_definition_fields_to_services_table.php'))->up();

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
            'request_config' => [
                'mode' => 'both',
                'fields' => [[
                    'key' => 'usage',
                    'label' => ['ar' => "الاستخدام {$suffix}", 'en' => "Usage {$suffix}"],
                    'type' => 'select',
                    'options' => ['ar' => ['منزلي'], 'en' => ['Residential']],
                    'help' => ['ar' => "مساعدة {$suffix}", 'en' => "Help {$suffix}"],
                    'required' => false,
                ]],
            ],
        ];
    }

    public function test_service_fields_persist_through_create_update_and_reopen(): void
    {
        $created = $this->postJson('/_test/admin/edu/services', $this->servicePayload('أ'))
            ->assertCreated()
            ->assertJsonPath('data.service.description', 'وصف مختصر أ')
            ->assertJsonPath('data.service.name_translations.en', 'Service أ')
            ->assertJsonPath('data.service.description_translations.en', 'Short description أ')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل أ')
            ->assertJsonPath('data.service.scope_of_work_translations.en', 'Scope of work أ')
            ->assertJsonPath('data.service.request_config.fields.0.label.en', 'Usage أ')
            ->assertJsonPath('data.service.request_config.fields.0.options.en.0', 'Residential');

        $serviceId = $created->json('data.service.id');
        $service = Service::where('uuid', $serviceId)->firstOrFail();
        $service->update(['request_config' => array_merge($service->request_config, ['_seed_key' => 'stable-seed-key'])]);

        $this->putJson("/_test/admin/edu/services/{$serviceId}", $this->servicePayload('ب'))
            ->assertOk()
            ->assertJsonPath('data.service.name', 'خدمة ب')
            ->assertJsonPath('data.service.description', 'وصف مختصر ب')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل ب')
            ->assertJsonPath('data.service.request_config.fields.0.label.ar', 'الاستخدام ب')
            ->assertJsonMissingPath('data.service.request_config._seed_key')
            ->assertJsonMissingPath('data.service.details');
        $this->assertSame(
            'stable-seed-key',
            Service::where('uuid', $serviceId)->firstOrFail()->request_config['_seed_key']
        );

        $this->getJson("/_test/admin/edu/services/{$serviceId}")
            ->assertOk()
            ->assertJsonPath('data.service.name', 'خدمة ب')
            ->assertJsonPath('data.service.description', 'وصف مختصر ب')
            ->assertJsonPath('data.service.scope_of_work', 'نطاق العمل ب')
            ->assertJsonPath('data.service.request_config.fields.0.options.ar.0', 'منزلي');
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

    public function test_display_details_are_not_stored_or_returned(): void
    {
        $payload = $this->servicePayload('Preview');
        $payload['details'] = ['pricing' => ['en' => 'Old display pricing']];
        $this->postJson('/_test/admin/edu/services', $payload)
            ->assertCreated()
            ->assertJsonMissingPath('data.service.details');
        $this->assertFalse(Schema::hasColumn('services', 'details'));
    }

    public function test_display_removal_preserves_request_settings_and_catalog_identity(): void
    {
        (require app_path('Domain/AppBase/Migrations/2026_11_22_000004_add_service_details_to_services_table.php'))->up();
        $service = Service::create($this->servicePayload('Migration'));
        DB::table('services')->where('id', $service->id)->update([
            'details' => json_encode(['_seed_key' => 'stable-catalog-key', 'pricing' => ['en' => 'Old price']]),
        ]);

        $migration = require app_path('Domain/AppBase/Migrations/2026_11_23_000001_remove_display_details_from_services_table.php');
        $migration->up();
        $migration->up();

        $this->assertFalse(Schema::hasColumn('services', 'details'));
        $config = $service->fresh()->request_config;
        $this->assertSame('stable-catalog-key', $config['_seed_key']);
        $this->assertSame('both', $config['mode']);
        $this->assertSame('usage', $config['fields'][0]['key']);
    }

    public function test_definition_fields_persist_and_reach_the_customer_service_options(): void
    {
        config(['app.brand' => 'taqa360']);
        $payload = array_merge($this->servicePayload('Definition'), [
            'deliverables' => ['ar' => ['خطة بحث', 'ملف قابل للتعديل'], 'en' => ['Research plan', 'Editable file']],
            'exclusions' => ['ar' => ['الطباعة'], 'en' => ['Printing']],
            'requirements' => ['ar' => ['جهز تعليمات الجامعة'], 'en' => ['Prepare university guidelines']],
            'duration' => ['ar' => 'ثلاثة أيام بعد تأكيد النطاق', 'en' => 'Three days after scope confirmation'],
            'pricing_mode' => 'fixed',
            'price' => 125.50,
        ]);
        $created = $this->postJson('/_test/admin/edu/services', $payload)
            ->assertCreated()
            ->assertJsonPath('data.service.deliverables.en.1', 'Editable file')
            ->assertJsonPath('data.service.pricing_mode', 'fixed')
            ->assertJsonPath('data.service.price', '125.50');
        $uuid = $created->json('data.service.id');

        $this->getJson("/_test/admin/edu/services/$uuid")
            ->assertOk()
            ->assertJsonPath('data.service.requirements.en.0', 'Prepare university guidelines')
            ->assertJsonPath('data.service.duration_translations.en', 'Three days after scope confirmation');
        $this->getJson(route('service-requests.services'))
            ->assertOk()
            ->assertJsonPath('data.services.0.exclusions.en.0', 'Printing')
            ->assertJsonPath('data.services.0.deliverables.en.0', 'Research plan')
            ->assertJsonPath('data.services.0.price', '125.50');

        $payload['pricing_mode'] = 'from';
        $payload['price'] = 75;
        $this->putJson("/_test/admin/edu/services/$uuid", $payload)
            ->assertOk()->assertJsonPath('data.service.pricing_mode', 'from')
            ->assertJsonPath('data.service.price', '75.00');
        $payload['pricing_mode'] = 'quote';
        $this->putJson("/_test/admin/edu/services/$uuid", $payload)
            ->assertOk()->assertJsonPath('data.service.price', null);
        $this->assertNull(Service::where('uuid', $uuid)->firstOrFail()->price);
    }

    public function test_displayed_price_is_required_for_fixed_and_starting_prices(): void
    {
        foreach (['fixed', 'from'] as $mode) {
            $payload = array_merge($this->servicePayload('Price'), ['pricing_mode' => $mode]);
            $this->postJson('/_test/admin/edu/services', $payload)
                ->assertUnprocessable()->assertJsonValidationErrors('price');
            $payload['price'] = -1;
            $this->postJson('/_test/admin/edu/services', $payload)
                ->assertUnprocessable()->assertJsonValidationErrors('price');
        }
    }
}
