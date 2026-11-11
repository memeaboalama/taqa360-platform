<?php

namespace Application\Admin\Livewire\SalesAgent\Tabs;

use App\Domain\Admin\Actions\Dashboard\SalesAgent\FetchSingleSalesAgentAction;
use App\Domain\Admin\Actions\Dashboard\SalesAgent\UpdateSalesAgentAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Support\Traits\Alerts\WithLocalizedAlerts;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Info extends Component
{
    use LivewireAlert;
    use WithLocalizedAlerts;

    public mixed $countries = [],$regions = [], $districts = [], $cities = [];
    public $name, $email, $phone, $status = 'active';
    public $salesAgent, $taken_commission;
    public $salesAgentId;
    public $country_id, $region_id, $city_id, $district_id = null;
    public mixed $company_id;
    public $Id_expire_date = null, $Id_number = null, $iban = null;
    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
    public function mount(int $id, FetchSingleSalesAgentAction $fetchSingleSalesAgentAction, FetchCountriesAction $fetchCountriesAction): void
    {
        $this->salesAgentId = $id;
        $this->salesAgent = $fetchSingleSalesAgentAction->handle($id);
        if ($this->salesAgent) {
            $this->name = $this->salesAgent->name;
            $this->email = $this->salesAgent->email;
            $this->status = $this->salesAgent->status->value;
            $this->phone = $this->salesAgent->phone;
            $this->taken_commission = $this->salesAgent->taken_commission;
            $this->company_id = $this->salesAgent->company_id;
            $this->countries = $fetchCountriesAction->handle();
            $this->country_id = $this->salesAgent->country_id;
            $this->regions = Region::where('country_id', $this->country_id)->get();
            $this->region_id = $this->salesAgent?->region_id;
            $this->cities = $this->region_id
                ? City::where('region_id', $this->region_id)->get()
                : City::where('country_id', $this->country_id)->get();
            $this->city_id = $this->salesAgent?->city_id;
            $this->districts = Distract::where('city_id', $this->salesAgent->city_id)->get();
            $this->district_id = $this->salesAgent?->district_id;
            $this->Id_expire_date = $this->salesAgent?->Id_expire_date;
            $this->Id_number = $this->salesAgent?->Id_number;
            $this->iban = $this->salesAgent?->iban;
            $this->emit('renderSelect');
        }else{
            abort(404);
        }
    }
    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:sales_agents,email,' . $this->salesAgentId,
            'phone' => 'required|string|min:2|max:200|unique:sales_agents,phone,' . $this->salesAgentId,
            'status' => 'required|string|in:active,inactive',
            'taken_commission' => 'required|numeric|min:0|max:100',
            'country_id' => 'nullable|int|min:1',
            'region_id' => 'nullable|int|min:1',
            'city_id' => 'nullable|int|min:1',
            'district_id' => 'nullable',
            'Id_expire_date' => 'nullable',
            'Id_number' => 'nullable',
            'iban' => 'nullable',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function updateCountry($country): void
    {
        if ($country !== '') {
            $country = json_decode($country);
            if (!$country || !isset($country->id) || !is_numeric($country->id)) {
                $this->addError('country_id', __('validation.exists', ['attribute' => __('admin::home.Country')]));
                return;
            }

            $this->country_id = (int) $country->id;
            $this->reset('region_id', 'city_id', 'district_id');
            $this->regions = Region::where('country_id', $this->country_id)->get();
            $this->cities = [];
            $this->districts = [];
        } else {
            $this->reset('country_id', 'region_id', 'city_id', 'district_id');
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

    public function store(UpdateSalesAgentAction $updateSalesAgentAction)
    {
        $data = $this->validate();
        $updated = DB::transaction(fn () => $updateSalesAgentAction->handle($this->salesAgentId, $data));

        if (!$updated) {
            $this->localizedAlert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->localizedAlert('success', __('admin::home.data_updated_successfully'));

        $listingPath = $updated->is_complete
            ? 'admin/salesagents'
            : 'admin/salesagents/pending';

        return redirect()->to(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(
            app()->getLocale(),
            url($listingPath)
        ));
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.tabs.info');
    }
}
