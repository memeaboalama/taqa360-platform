<?php

namespace Application\Marketplace\Livewire\Branch\Tabs;

use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\StoreBranchAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class Category extends Component
{
    use LivewireAlert;

    public mixed $counties = [], $districts = [], $cities = [];
    public $priority = 1, $country_id, $city_id, $district_id = 1;
    public string $status = 'active', $type = 'fixed';
    public array $title = ['en' => null], $description = ['en' => null], $address_name = ['en' => null];

    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
    protected GetDistrictsAction $getDistrictsAction;


    public function mount(FetchCountriesAction $fetchCountriesAction): void
    {
        $this->counties = $fetchCountriesAction->handle();
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',

            'description' => 'required|array',
            'description.*' => 'required|string|min:2|max:120',

            'address_name' => 'required|array',
            'address_name.*' => 'required|string|min:2|max:120',


            'priority' => 'required|int|min:1',
            'type' => 'required|string|in:fixed,mobile',
        ];
    }

    public function updateCountry($country): void
    {
        if($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'cities');
            $this->country_id = $country->id;
            $this->cities = City::where('country_id', $country->id)->get();
//            $this->emit('renderCities', $country->cities);
        }
    }

    public function updatedCityId($city_id): void
    {

        $this->reset('district_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();

    }

    public function updateDistrict($district_id): void
    {
        $this->district_id = $district_id;
    }

    public function store(StoreBranchAction $storeBranchAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeBranchAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createBranch');
        $this->alert('success', 'Branch Created Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.tabs.category');
    }
}
