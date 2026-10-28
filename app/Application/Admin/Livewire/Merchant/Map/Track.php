<?php

namespace Application\Admin\Livewire\Merchant\Map;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Track extends Component
{
    use LivewireAlert;

    public array $branches = [];

    public function mount(BranchRepository $branchRepository)
    {
        $list = $branchRepository
            ->all(cols: ['id', 'merchant_id', 'title', 'latitude', 'longitude'], relations: ['merchant']);


        foreach ($list as $key => $value)
        {
            if (isset($value->latitude) && $value->latitude != null)
            {
                $this->branches[$key]['id'] = $value->id;
                $this->branches[$key]['merchant_id'] = $value->merchant_id;
                $this->branches[$key]['name'] = $value->title;
                $this->branches[$key]['position'] = ['lat' => (double)$value?->latitude, 'lng' => (double)$value?->longitude];
                $this->branches[$key]['restaurant_name'] = $value->merchant?->company_name;
                $this->branches[$key]['draggable'] = false;
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.map.track');
    }
}
