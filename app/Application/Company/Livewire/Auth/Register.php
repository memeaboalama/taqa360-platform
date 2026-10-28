<?php

namespace Application\Company\Livewire\Auth;

use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\Provider\Actions\Dashboard\Auth\RegisterAction;
use App\Providers\RouteServiceProvider;
use App\Support\Rules\IsValidPhone;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Register extends Component
{
    use LivewireAlert;

    public string $name, $email, $password, $password_confirmation, $phone, $company_name, $type;
    public mixed $counties = [], $districts = [], $cities = [];
    public $country_id = 1, $city_id, $district_id;
    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:merchants,email|min:3',
            'password' => 'required|string|confirmed|min:4',
            'phone' => ['required','numeric','unique:companies,phone','digits_between:9,10', new isValidPhone],
            'company_name' => 'required|string',
            'name' => 'required|string',
            'country_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'district_id' => 'required',
        ];
    }

    public function mount(FetchCountriesAction $fetchCountriesAction)
    {
        $this->countries = $fetchCountriesAction->handle();
    }

    public function register(RegisterAction $registerAction)
    {
        $data = $this->validate();
        $res = $registerAction->handle($data);
        if ($res) return redirect(RouteServiceProvider::COMPANY_HOME);
    }

    public function updateCountry($country): void
    {
        if ($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'cities');
            $this->country_id = $country->id;
            $this->cities = City::where('country_id', $country->id)->get();

            //        $this->emit('renderCities', $country->cities);
        } else {
            $this->cities = [];
            $this->districts = [];
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
        $this->district_id = (int)$district_id;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.auth.register');
    }
}
