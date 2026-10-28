<?php

namespace Application\Company\Livewire\Driver\Map;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Track extends Component
{
    use LivewireAlert;

    public array $drivers = [];


    public function mount(DriverRepository $driverRepository)
    {
        $list = $driverRepository
            ->all(cols: ['id', 'name', 'location', 'dialcode', 'phone'],
                condition: ['company_id' => auth()->guard('company')->user()->id,
                    'network_status'=> 1,
                    'status' => StatusEnum::ACTIVE->value])
            ->toArray();
        foreach ($list as $key => $value)
        {
            if (isset($value['location']['coordinates']))
            {
                $this->drivers[$key]['id'] = $value['id'];
                $this->drivers[$key]['name'] = $value['name'];
                $this->drivers[$key]['position'] = ['lat' => $value['location']['coordinates'][1], 'lng' => $value['location']['coordinates'][0]];
                $this->drivers[$key]['phone'] = $value['dialcode'] . $value['phone'];
                $this->drivers[$key]['draggable'] = false;
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.driver.map.track');
    }
}
