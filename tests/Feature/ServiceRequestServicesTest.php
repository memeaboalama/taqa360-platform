<?php

namespace Tests\Feature;

use App\Application\Admin\Controllers\Api\Edu\ServiceController;
use App\Application\Educational\Controllers\ServiceRequestWebController;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Support\Services\ImagesServices\ImageService;
use App\Support\Services\TaqaServiceRequestConfiguration;
use Database\Seeders\Educator\TaqaEnergyServiceSeeder;
use Domain\AppBase\Models\Edu\Service;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use App\Domain\User\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
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
        (require app_path('Domain/AppBase/Migrations/2026_11_13_000001_add_scope_of_work_to_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_22_000002_add_request_config_to_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_22_000004_add_service_details_to_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_23_000001_remove_display_details_from_services_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_23_000002_add_service_definition_fields_to_services_table.php'))->up();
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

    public function test_service_options_supply_survey_fields_when_service_config_is_empty(): void
    {
        config(['app.brand' => 'taqa360']);
        $service = $this->service();
        $service->update([
            'name' => [
                'ar' => 'معاينة الموقع وتقييم احتياجات الطاقة',
                'en' => 'Site Survey & Energy Assessment',
            ],
            'request_config' => [],
        ]);

        $this->getJson(route('service-requests.services'))
            ->assertOk()
            ->assertJsonPath('data.services.0.request_config.fields.0.key', 'goal')
            ->assertJsonPath('data.services.0.request_config.fields.1.key', 'existing')
            ->assertJsonPath('data.services.0.request_config.fields.2.key', 'loads');
    }

    public function test_taqa_service_seeder_populates_the_catalog_without_duplicates(): void
    {
        $seeder = new TaqaEnergyServiceSeeder();
        $seeder->run();
        $seeder->run();

        $response = app(ServiceController::class)->options()->getData(true);

        $this->assertCount(10, $response['data']['services']);
        $this->assertSame(10, Service::where('status', 'active')->count());
        $this->assertNotEmpty($response['data']['services'][0]['request_config']['fields']);
        $survey = Service::where('name->en', 'Site Survey & Energy Assessment')->firstOrFail();
        $this->assertSame(
            ['goal', 'existing', 'loads'],
            array_column($survey->request_config['fields'], 'key')
        );
        $this->assertArrayNotHasKey('details', $response['data']['services'][0]);
        $this->assertArrayNotHasKey('_seed_key', $response['data']['services'][0]['request_config']);
    }

    public function test_catalog_supplies_missing_questions_for_every_standard_service(): void
    {
        config(['app.brand' => 'taqa360']);
        (new TaqaEnergyServiceSeeder())->run();
        $defaults = TaqaServiceRequestConfiguration::defaults();
        Service::query()->update(['request_config' => json_encode([])]);

        $services = $this->getJson(route('service-requests.services'))
            ->assertOk()->json('data.services');

        $this->assertCount(10, $services);
        foreach ($services as $item) {
            $service = Service::where('uuid', $item['id'])->firstOrFail();
            $expected = $defaults[$service->getTranslation('name', 'en')];
            $this->assertSame($expected, $item['request_config']);
        }
    }

    public function test_missing_questions_preserve_customized_fields_and_service_identity(): void
    {
        config(['app.brand' => 'taqa360']);
        (new TaqaEnergyServiceSeeder())->run();
        $service = Service::where('name->en', 'Solar Panel Cleaning')->firstOrFail();
        $customField = ['key' => 'count', 'label' => 'Custom count', 'type' => 'number', 'required' => true];
        $extraField = ['key' => 'extra', 'label' => 'Extra question', 'type' => 'text'];
        $service->update([
            'name' => ['ar' => 'Custom service', 'en' => 'Custom service'],
            'request_config' => array_merge($service->request_config, ['mode' => 'both', 'fields' => [$customField, $extraField]]),
        ]);

        $config = TaqaServiceRequestConfiguration::forService($service);
        $this->assertSame('both', $config['mode']);
        $this->assertSame($customField, $config['fields'][0]);
        $this->assertSame($extraField, $config['fields'][1]);
        $this->assertSame(['count', 'extra', 'mount', 'access', 'last'], array_column($config['fields'], 'key'));
    }

    public function test_submission_validates_questions_restored_from_service_defaults(): void
    {
        config(['app.brand' => 'taqa360']);
        (new TaqaEnergyServiceSeeder())->run();
        $service = Service::where('name->en', 'Solar Panel Cleaning')->firstOrFail();
        $service->update(['request_config' => []]);
        $this->mock(CreateEducationalRequestAction::class)->shouldNotReceive('handle');

        $this->postJson('/_test/service-request', [
            'title' => 'Panel cleaning', 'description' => 'Clean the panels',
            'service_id' => $service->uuid, 'delivery_mode' => 'onsite',
            'property_type' => 'home', 'site_city' => 'riyadh', 'service_address' => 'Site address',
            'is_flexible' => '1', 'preferred_period' => 'flexible',
            'contact_name' => 'Contact', 'contact_phone' => '+966500000000',
            'service_answers' => ['count' => '-1'],
        ])->assertUnprocessable()->assertJsonValidationErrors('service_answers.count');
    }

    public function test_taqa_service_seeder_preserves_admin_edits(): void
    {
        $seeder = new TaqaEnergyServiceSeeder();
        $seeder->run();
        $service = Service::firstOrFail();
        $service->update([
            'name' => ['ar' => 'اسم مخصص', 'en' => 'Custom name'],
            'request_config' => array_merge($service->request_config, ['show_urgency' => true]),
            'status' => 'inactive',
        ]);

        $seeder->run();

        $service->refresh();
        $this->assertSame('Custom name', $service->getTranslation('name', 'en'));
        $this->assertTrue($service->request_config['show_urgency']);
        $this->assertSame('inactive', $service->status->value);
        $this->assertSame(10, Service::count());
    }

    public function test_service_details_api_returns_the_selected_service_content(): void
    {
        config(['app.brand' => 'taqa360']);
        $service = $this->service();
        $service->update([
            'scope_of_work' => ['en' => 'Assessment scope'],
        ]);

        $this->getJson(route('service-requests.service-data', ['serviceUuid' => $service->uuid]))
            ->assertOk()
            ->assertJsonPath('data.service.id', (string) $service->uuid)
            ->assertJsonPath('data.service.scope_of_work_translations.en', 'Assessment scope')
            ->assertJsonMissingPath('data.service.details');
    }

    public function test_service_details_page_uses_the_selected_active_service(): void
    {
        config(['app.brand' => 'taqa360']);
        (require app_path('Domain/AppBase/Migrations/2023_06_12_203944_create_currencys_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2023_10_17_193339_create_general_settings_table.php'))->up();
        DB::table('currencies')->insert([
            'title' => json_encode(['ar' => 'ريال سعودي', 'en' => 'Saudi Riyal']),
            'iso' => 'sar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Cache::forget('store_currency_iso');

        (new TaqaEnergyServiceSeeder())->run();
        $service = Service::firstOrFail();

        $this->get(route('service-requests.catalog.details', ['serviceUuid' => $service->uuid]))
            ->assertOk()
            ->assertViewIs('ecommerce::pages.service-details')
            ->assertViewHas('serviceUuid', $service->uuid)
            ->assertSee(route('service-requests.service-data', ['serviceUuid' => $service->uuid], false));
    }

    public function test_taqa_form_rejects_non_array_service_answers(): void
    {
        config(['app.brand' => 'taqa360']);
        $service = $this->service();
        $service->update(['request_config' => ['mode' => 'remote', 'fields' => []]]);
        $this->mock(CreateEducationalRequestAction::class)->shouldNotReceive('handle');

        $this->postJson('/_test/service-request', [
            'title' => 'Test request',
            'description' => 'Request description',
            'service_id' => $service->uuid,
            'delivery_mode' => 'remote',
            'is_flexible' => '1',
            'preferred_period' => 'flexible',
            'contact_name' => 'Test contact',
            'contact_phone' => '+966500000000',
            'service_answers' => 'invalid',
        ])->assertUnprocessable();
    }

    public function test_taqa_form_validates_select_answers_against_the_current_language(): void
    {
        config(['app.brand' => 'taqa360']);
        app()->setLocale('en');
        $service = $this->service();
        $service->update([
            'request_config' => [
                'mode' => 'remote',
                'fields' => [[
                    'key' => 'usage',
                    'label' => ['ar' => 'الاستخدام', 'en' => 'Usage'],
                    'type' => 'select',
                    'options' => ['ar' => ['منزلي'], 'en' => ['Residential']],
                    'required' => true,
                ]],
            ],
        ]);
        $this->mock(CreateEducationalRequestAction::class)->shouldNotReceive('handle');

        $this->postJson('/_test/service-request', [
            'title' => 'Test request',
            'description' => 'Request description',
            'service_id' => $service->uuid,
            'delivery_mode' => 'remote',
            'is_flexible' => '1',
            'preferred_period' => 'flexible',
            'contact_name' => 'Test contact',
            'contact_phone' => '+966500000000',
            'service_answers' => ['usage' => 'منزلي'],
        ])->assertUnprocessable();
    }

    public function test_taqa_form_forwards_survey_answers_for_storage(): void
    {
        config(['app.brand' => 'taqa360']);
        $service = $this->service();
        $answers = [
            'goal' => 'تجهيز منظومة جديدة',
            'existing' => 'لا',
            'loads' => 'مكيف وثلاجة',
        ];
        $service->update([
            'name' => [
                'ar' => 'معاينة الموقع وتقييم احتياجات الطاقة',
                'en' => 'Site Survey & Energy Assessment',
            ],
            'request_config' => ['mode' => 'onsite', 'solar' => true],
        ]);
        $this->mock(CreateEducationalRequestAction::class, function ($mock) use ($answers) {
            $mock->shouldReceive('handle')->once()->withArgs(fn ($data) =>
                $data['service_answers'] === $answers
            )->andReturn(new EducationalRequest());
        });

        $this->postJson('/_test/service-request', [
            'title' => 'معاينة الموقع',
            'description' => 'تقييم احتياج الموقع للطاقة.',
            'service_id' => $service->uuid,
            'solar_system_type' => 'on-grid',
            'delivery_mode' => 'onsite',
            'property_type' => 'home',
            'site_city' => 'riyadh',
            'service_address' => 'الرياض',
            'is_flexible' => '1',
            'preferred_period' => 'flexible',
            'contact_name' => 'عميل تجريبي',
            'contact_phone' => '0501234567',
            'service_answers' => $answers,
        ])->assertOk()->assertJsonPath('success', true);
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
        $repository->shouldReceive('store')->twice()->withArgs(fn ($data) =>
            $data['service_id'] === $service->id && $data['type'] === 'general'
        )->andReturn(new EducationalRequest());
        $action = new CreateEducationalRequestAction($repository, Mockery::mock(ImageService::class));
        $action->handle(['title' => 'Test', 'description' => 'Test request', 'user_id' => 1, 'service_id' => $service->id]);
        $action->handle(['title' => 'Test', 'description' => 'Test request', 'user_id' => 1, 'service_id' => $service->uuid]);

        $request = new EducationalRequest(['type' => EducationalRequestType::GENERAL]);
        $request->setRelation('service', $service);
        $this->assertSame($service->name, $request->service_type_label);
        $request->setRelation('service', null);
        $this->assertSame(EducationalRequestType::GENERAL->trans(), $request->service_type_label);
    }

    public function test_service_answers_are_persisted_as_json_on_the_request(): void
    {
        config(['app.brand' => 'taqa360']);
        Schema::create('educational_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->unsignedBigInteger('requester_id');
            $table->text('description');
            $table->string('type')->default('general');
            $table->string('status')->default('pending');
            $table->string('execution_status')->default('pending');
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('agreed_amount', 10, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->text('final_agreement')->nullable();
            $table->string('urgency_level')->default('normal');
            $table->text('additional_notes')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('project_site_type')->nullable();
            $table->string('solar_system_type')->nullable();
            $table->string('delivery_mode')->nullable();
            $table->string('property_type')->nullable();
            $table->string('site_city')->nullable();
            $table->string('site_city_other')->nullable();
            $table->text('service_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('access_notes')->nullable();
            $table->date('preferred_date')->nullable();
            $table->boolean('is_flexible')->nullable();
            $table->string('preferred_period')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->json('service_answers')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        $service = $this->service();
        $answers = [
            'goal' => 'تجهيز منظومة جديدة',
            'existing' => 'لا',
            'loads' => 'مكيف وثلاجة',
        ];
        $action = new CreateEducationalRequestAction(
            new EducationalRequestRepository(new EducationalRequest()),
            Mockery::mock(ImageService::class)
        );

        $request = $action->handle([
            'title' => 'معاينة الموقع',
            'description' => 'تقييم احتياج الموقع للطاقة.',
            'user_id' => 1,
            'service_id' => $service->id,
            'service_answers' => $answers,
            'delivery_mode' => 'onsite',
            'property_type' => 'home',
            'site_city' => 'other',
            'site_city_other' => 'Test city',
            'service_address' => 'Test address',
            'latitude' => '24.7136000',
            'longitude' => '46.6753000',
            'access_notes' => 'Use the main entrance',
            'preferred_date' => now()->addDay()->toDateString(),
            'is_flexible' => false,
            'preferred_period' => 'morning',
            'contact_name' => 'Site contact',
            'contact_phone' => '+966500000000',
            'budget' => '1500.00',
            'solar_system_type' => 'hybrid',
        ]);

        $stored = $request->fresh();
        $this->assertSame($answers, $stored->service_answers);
        foreach ([
            'delivery_mode' => 'onsite', 'property_type' => 'home',
            'site_city' => 'other', 'site_city_other' => 'Test city',
            'service_address' => 'Test address', 'access_notes' => 'Use the main entrance',
            'latitude' => '24.7136000', 'longitude' => '46.6753000',
            'is_flexible' => false, 'preferred_period' => 'morning',
            'contact_name' => 'Site contact', 'contact_phone' => '+966500000000',
            'budget' => '1500.00', 'solar_system_type' => 'hybrid',
        ] as $field => $expected) {
            $this->assertSame($expected, $stored->$field, $field);
        }
        $this->assertSame(now()->addDay()->toDateString(), $stored->preferred_date->toDateString());
    }

    public function test_user_profile_date_of_birth_is_mass_assignable_and_cast_as_date(): void
    {
        $user = new User(['date_of_birth' => '1990-04-15']);

        $this->assertSame('1990-04-15', $user->date_of_birth->toDateString());
    }

    public function test_payment_correction_migration_creates_the_account_details_table(): void
    {
        Schema::create('merchant_orders', fn (Blueprint $table) => $table->id());
        Schema::create('admins', fn (Blueprint $table) => $table->id());

        $migration = require app_path('Domain/MarketPlace/Migrations/2026_11_16_000001_create_payment_correction_requests_table.php');
        $migration->up();

        $this->assertTrue(Schema::hasTable('payment_correction_requests'));
        $this->assertTrue(Schema::hasColumn('payment_correction_requests', 'movement_id'));
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
