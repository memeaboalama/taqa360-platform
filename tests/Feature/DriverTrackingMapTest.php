<?php

namespace Tests\Feature;

use App\Domain\Driver\Repositories\DriverRepository;
use Application\Admin\Livewire\Driver\Map\Track;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Livewire;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Tests\TestCase;

class DriverTrackingMapTest extends TestCase
{
    public function test_tracking_map_shows_only_drivers_with_valid_locations_and_refreshes(): void
    {
        $drivers = new Collection([
            (object) [
                'id' => 1,
                'name' => 'Mapped driver',
                'location' => new Point(24.774265, 46.738586),
                'dialcode' => '966',
                'phone' => '500000001',
            ],
            (object) [
                'id' => 2,
                'name' => 'Driver without location',
                'location' => null,
                'dialcode' => '966',
                'phone' => '500000002',
            ],
            (object) [
                'id' => 3,
                'name' => 'Driver with invalid location',
                'location' => new Point(91, 181),
                'dialcode' => '966',
                'phone' => '500000003',
            ],
        ]);

        $repository = \Mockery::mock(DriverRepository::class);
        $repository->shouldReceive('all')
            ->twice()
            ->with(
                ['id', 'name', 'location', 'dialcode', 'phone'],
                [],
                ['network_status' => 1, 'status' => 'active']
            )
            ->andReturn($drivers);
        $this->instance(DriverRepository::class, $repository);

        $expectedDrivers = [[
            'id' => 1,
            'name' => 'Mapped driver',
            'position' => ['lat' => 24.774265, 'lng' => 46.738586],
            'phone' => '966500000001',
        ]];

        Livewire::test(Track::class)
            ->assertSet('drivers', $expectedDrivers)
            ->assertSee('wire:poll.15s', false)
            ->call('refreshDrivers')
            ->assertSet('drivers', $expectedDrivers)
            ->assertDispatchedBrowserEvent('drivers-updated', ['drivers' => $expectedDrivers]);
    }
}
