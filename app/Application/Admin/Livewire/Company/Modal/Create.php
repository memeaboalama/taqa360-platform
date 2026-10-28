<?php

namespace Application\Admin\Livewire\Company\Modal;

use App\Domain\Admin\Actions\Dashboard\Company\StoreCompanyAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $company_name, $name = "", $email, $password, $phone, $password_confirmation, $description, $status = 'active', $type = "drivers";
    public  $country_id = 1, $city_id, $district_id;
    public mixed $counties = [], $districts = [], $cities = [];
    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];

    public function mount(FetchCountriesAction $fetchCountriesAction)
    {
        $this->counties = $fetchCountriesAction->handle();
    }
    protected function rules(): array
    {
        return [
            'company_name' => 'required',
            'email' => 'required',
            'description' => 'required|string|min:2|max:300',
            'phone' => 'required',
            'country_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'district_id' => 'required',
            'password' => 'required|string|min:6|max:50|confirmed',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function updateCountry($country): void
    {
        if($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'cities');
            $this->country_id = $country->id;
            $this->cities = City::where('country_id', $country->id)->get();
        }else{
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
        $this->district_id = $district_id;
    }

    public function store(StoreCompanyAction $storeCompanyAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeCompanyAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCompany');
        $this->reset('company_name','country_id', 'city_id', 'district_id', 'email', 'description', 'phone', 'password', 'status', 'password_confirmation');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.modal.create');
    }
}
