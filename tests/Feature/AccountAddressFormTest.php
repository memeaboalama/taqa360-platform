<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountAddressFormTest extends TestCase
{
    private function formData(): array
    {
        return [
            'countries' => collect([(object) ['id' => 1, 'title' => 'Saudi Arabia']]),
            'regionsByCountry' => [1 => [['id' => 2, 'title' => 'Riyadh region']]],
            'citiesByRegion' => [2 => [['id' => 3, 'title' => 'Riyadh']]],
            'districtsByCity' => [3 => [['id' => 5, 'title' => 'District']]],
            'useNewAccountLayout' => true,
            'insideModal' => true,
        ];
    }

    private function locationData(string $html): array
    {
        $this->assertSame(1, preg_match(
            '/<script type="application\/json" class="address-location-data">(.*?)<\/script>/s',
            $html,
            $matches
        ));

        return json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_create_modal_renders_region_before_city_and_valid_location_data(): void
    {
        $html = view('ecommerce::pages.account._create_address', $this->formData())->render();

        $this->assertStringContainsString('data-address-modal-form="true"', $html);
        $this->assertStringContainsString('data-bs-dismiss="modal"', $html);
        $this->assertStringContainsString('data-address-map', $html);
        $this->assertStringContainsString('name="latitude"', $html);
        $this->assertStringContainsString('name="longitude"', $html);
        $this->assertLessThan(strpos($html, 'name="city_id"'), strpos($html, 'name="region_id"'));
        $data = $this->locationData($html);
        $this->assertSame('Riyadh region', $data['regionsByCountry'][1][0]['title']);
        $this->assertSame('Riyadh', $data['citiesByRegion'][2][0]['title']);
        $this->assertSame('District', $data['districtsByCity'][3][0]['title']);
        $this->assertStringContainsString('select name="distract_id"', $html);
        $this->assertStringNotContainsString('name="district_name"', $html);
        $this->assertSame('', $data['initialCityId']);
    }

    public function test_edit_modal_preserves_existing_country_region_city_and_escapes_address(): void
    {
        $address = (object) [
            'id' => 4,
            'title' => 'Home',
            'country_id' => 1,
            'city_id' => 3,
            'city' => (object) ['region_id' => 2],
            'district_name' => 'District',
            'distract_id' => 5,
            'address' => '<script>alert(1)</script>',
            'latitude' => '0',
            'longitude' => '0',
            'is_default' => true,
        ];
        $html = view('ecommerce::pages.account._edit_address', $this->formData() + compact('address'))->render();

        $this->assertStringContainsString('data-address-modal-form="true"', $html);
        $this->assertStringContainsString('name="_method" value="PUT"', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringContainsString('name="latitude" value="0"', $html);
        $this->assertStringContainsString('name="longitude" value="0"', $html);
        $data = $this->locationData($html);
        $this->assertSame(1, $data['initialCountryId']);
        $this->assertSame(2, $data['initialRegionId']);
        $this->assertSame(3, $data['initialCityId']);
        $this->assertSame(5, $data['initialDistrictId']);
    }

    public function test_store_rejects_a_district_that_does_not_belong_to_the_selected_city(): void
    {
        config(['database.default' => 'address_test', 'database.connections.address_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        foreach (['countries', 'regions', 'cities', 'distracts'] as $name) {
            \Illuminate\Support\Facades\Schema::create($name, function ($table) {
                $table->id();
                $table->unsignedInteger('country_id')->nullable();
                $table->unsignedInteger('region_id')->nullable();
                $table->unsignedInteger('city_id')->nullable();
                $table->string('status')->default('active');
            });
        }
        \Illuminate\Support\Facades\DB::table('countries')->insert(['id' => 1]);
        \Illuminate\Support\Facades\DB::table('regions')->insert(['id' => 2, 'country_id' => 1]);
        \Illuminate\Support\Facades\DB::table('cities')->insert(['id' => 3, 'country_id' => 1, 'region_id' => 2]);
        \Illuminate\Support\Facades\DB::table('distracts')->insert(['id' => 5, 'city_id' => 9]);
        \Illuminate\Support\Facades\Route::post('/_test/account-address', [
            \App\Application\Ecommerce\Controllers\AccountController::class, 'storeAddress',
        ]);

        $this->postJson('/_test/account-address', [
            'title' => 'Home', 'country_id' => 1, 'region_id' => 2,
            'city_id' => 3, 'distract_id' => 5, 'address' => 'Street',
        ])->assertUnprocessable()
            ->assertJsonPath('status', false)
            ->assertJsonStructure(['data' => ['distract_id']]);
    }
}
