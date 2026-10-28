<?php

namespace Application\Admin\Livewire\Company\Tabs\Info;

use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateBasicInfoAction;
use App\Domain\Provider\Actions\Dashboard\Company\UpdateCompanyInfoAction;
use App\Domain\Provider\Models\Company;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Info extends Component
{

    use LivewireAlert;
    public mixed $countries = [],$regions=[], $districts = [], $cities = [];
    public $company_name, $name = "", $email, $password, $phone, $password_confirmation, $description, $status = 'active', $type = "drivers";
    public $country_id = 1,$region_id=1, $city_id = 1, $district_id = null;
    public mixed $company_id;
    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];


    public function mount(int $company_id, FetchCountriesAction $fetchCountriesAction): void
    {
        $this->company = Company::findOrFail($company_id);
        $this->country_id = 1;
        $this->email = $this->company?->email;
        $this->phone = $this->company?->phone;
        $this->company_id = $this->company->company_id;
        $this->company_name = $this->company?->company_name;
        $this->description = $this->company?->description;
        $this->countries = $fetchCountriesAction->handle();
        $this->regions = Region::where('country_id', $this->company->country_id)->get();
        $this->region_id = $this->company?->region_id;
        $this->cities = City::where('country_id', $this->company->country_id)->get();
        $this->city_id = $this->company?->city_id;
        $this->districts = Distract::where('city_id', $this->company->city_id)->get();
        $this->district_id = $this->company?->district_id;
        $this->emit('renderSelect');
    }


    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:companies,email,' . $this->company->id,
            'phone' => 'required|unique:companies,phone,' . $this->company->id,
            'company_name' => 'required|string',
            'country_id' => 'nullable|int|min:1',
            'city_id' => 'nullable|int|min:1',
            'region_id' => 'nullable|int|min:1',
            'district_id' => 'nullable',
            'password' => 'nullable|string|min:6|max:50|confirmed',
            'description' => 'required|string|min:2|max:300',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateBasicInfo(UpdateCompanyInfoAction $updateCompanyInfoAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateCompanyInfoAction->handle($this->company->id, $data);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function updateCountry($country): void
    {
        if($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'regions');
            $this->country_id = $country->id;
            $this->regions = Region::where('country_id', $country->id)->get();
        }else{
            $this->regions = [];
            $this->cities = [];
            $this->districts = [];
        }
    }    
    public function updatedRegionId($region_id): void
    {
        $this->reset('city_id');
        $this->region_id = $region_id;
        $this->cities = City::where('region_id', $region_id)->get();
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

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.info.info');
    }
}
