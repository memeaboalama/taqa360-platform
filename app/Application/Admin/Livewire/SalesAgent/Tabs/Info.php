<?php

namespace Application\Admin\Livewire\SalesAgent\Tabs;

use App\Domain\Admin\Actions\Dashboard\salesAgent\UpdateDriverAction;
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
    public $country_id = 1,$region_id, $city_id = 1, $district_id = null;
    public mixed $company_id;
    public $Id_expire_date = null, $Id_number = null, $iban = null;
    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
    protected UpdateSalesAgentAction $updateDriverAction;

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
            $this->country_id = 1;
            $this->countries = $fetchCountriesAction->handle();
            $this->regions = Region::where('country_id', $this->country_id)->get();
            $this->region_id = $this->salesAgent?->region_id;
            $this->cities = City::where('country_id', $this->salesAgent->country_id)->get();
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
            'email' => 'required|string|min:2|max:200|unique:drivers,email,' . $this->salesAgentId,
            'phone' => 'required|string|min:2|max:200|unique:drivers,phone,'. $this->salesAgentId,
            'status' => 'required|string|in:active,inactive',
            'taken_commission' => 'required',
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

    public function store(UpdateSalesAgentAction $updateSalesAgentAction)
    {
        $data = $this->validate();
        DB::beginTransaction();
        try {
            $updateSalesAgentAction->handle($this->salesAgentId, $data);
            DB::commit();
            $this->localizedAlert('success', __('admin::home.data_updated_successfully'));
            return redirect()->to(url('admin/salesagents'));
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->localizedAlert('error', __('admin::home.something_went_wrong'));
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.tabs.info');
    }
}
