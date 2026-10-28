<?php

namespace Application\Admin\Livewire\Driver\Map;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Track extends Component
{
    use LivewireAlert;

    public array $drivers = [];
    public string $lastUpdatedAt = '';


    public function mount(DriverRepository $driverRepository): void
    {
        $this->loadDrivers($driverRepository);
        $this->lastUpdatedAt = now()->format('H:i:s');
    }

    public function refreshDrivers(DriverRepository $driverRepository): void
    {
        $this->loadDrivers($driverRepository);
        $this->lastUpdatedAt = now()->format('H:i:s');
        $this->dispatchBrowserEvent('drivers-updated', [
            'drivers' => $this->drivers,
        ]);
    }

    private function loadDrivers(DriverRepository $driverRepository): void
    {
        $list = $driverRepository->all(
            cols: ['id', 'name', 'location', 'dialcode', 'phone'],
            condition: [
                'network_status' => 1,
                'status' => StatusEnum::ACTIVE->value,
            ],
        );

        $this->drivers = [];

        foreach ($list as $driver) {
            $coordinates = $driver->location?->getCoordinates();
            if (!is_array($coordinates) || count($coordinates) < 2) {
                continue;
            }

            $longitude = $coordinates[0];
            $latitude = $coordinates[1];
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                continue;
            }

            $latitude = (float) $latitude;
            $longitude = (float) $longitude;
            if (!is_finite($latitude) || !is_finite($longitude)
                || $latitude < -90 || $latitude > 90
                || $longitude < -180 || $longitude > 180) {
                continue;
            }

            $this->drivers[] = [
                'id' => $driver->id,
                'name' => $driver->name,
                'position' => ['lat' => $latitude, 'lng' => $longitude],
                'phone' => $driver->dialcode . $driver->phone,
            ];
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.map.track');
    }
}
