<?php

namespace Tests\Feature;

use Application\Admin\Livewire\Driver\Search\Drivers as SearchDrivers;
use Application\Admin\Livewire\Driver\Table\All as DriversTable;
use Application\Admin\Livewire\Driver\Table\Filter as DriversFilter;
use Domain\Driver\Models\Driver;
use Livewire\Livewire;
use Tests\TestCase;

class DriverFilterChainTest extends TestCase
{
    public function test_filter_emits_refresh_drivers_with_status()
    {
        Livewire::test(DriversFilter::class)
            ->set('status', 'active')
            ->call('filterStatus')
            ->assertEmitted('refreshDrivers', ['status' => 'active']);
    }

    public function test_search_select_driver_emits_refresh_driver_filter()
    {
        $driver = Driver::where('name', 'like', '%mmmm%')->first();
        $this->assertNotNull($driver, 'test driver not found');

        Livewire::test(SearchDrivers::class)
            ->set('query', 'mmmm')
            ->call('selectDriver', $driver->id)
            ->assertEmitted('refreshDriverFilter');
    }

    public function test_filter_receives_driver_and_emits_refresh_drivers()
    {
        $driver = Driver::where('name', 'like', '%mmmm%')->first();

        Livewire::test(DriversFilter::class)
            ->call('refreshDriverFilter', $driver)
            ->assertEmitted('refreshDrivers', ['driver_id' => $driver->id]);
    }

    public function test_table_filters_when_refresh_drivers_called()
    {
        $driver = Driver::where('name', 'like', '%mmmm%')->first();
        $this->assertNotNull($driver);

        Livewire::test(DriversTable::class)
            ->call('refreshDrivers', ['driver_id' => $driver->id])
            ->assertSee($driver->name)
            ->assertDontSee('8765');
    }

    public function test_filter_company_with_empty_selection_does_not_500()
    {
        Livewire::test(DriversFilter::class)
            ->set('company_id', '')
            ->call('filterCompany')
            ->assertEmitted('refreshDrivers', ['company_id' => null]);
    }

    public function test_filter_company_with_selected_company_works()
    {
        $company = \App\Domain\Provider\Models\Company::first();
        $companyId = $company?->id;
        $this->assertNotNull($companyId, 'no company found');

        Livewire::test(DriversFilter::class)
            ->set('company_id', $companyId)
            ->call('filterCompany')
            ->assertEmitted('refreshDrivers', ['company_id' => $companyId]);
    }

    public function test_filter_status_with_empty_selection_emits_null()
    {
        Livewire::test(DriversFilter::class)
            ->set('status', '')
            ->call('filterStatus')
            ->assertEmitted('refreshDrivers', ['status' => null]);
    }

    public function test_filter_network_status_with_empty_selection_emits_null()
    {
        Livewire::test(DriversFilter::class)
            ->set('network_status', '')
            ->call('filterNetworkStatus')
            ->assertEmitted('refreshDrivers', ['network_status' => null]);
    }

    public function test_status_dropdowns_render_as_strings_after_rerender()
    {
        Livewire::test(DriversFilter::class)
            ->call('filterStatus')
            ->assertSee('online', false)
            ->assertSee('offline', false)
            ->assertSee('active', false)
            ->assertSee('inactive', false);
    }
}