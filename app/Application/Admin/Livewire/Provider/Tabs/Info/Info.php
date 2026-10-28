<?php

namespace Application\Admin\Livewire\Provider\Tabs\Info;

use App\Domain\AppBase\Models\Location\Country;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateBasicInfoAction;
use App\Domain\Provider\Actions\Dashboard\Company\UpdateCompanyInfoAction;
use App\Domain\Provider\Actions\Dashboard\Provider\FetchSingleProviderAction;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\Provider;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Info extends Component
{

    use LivewireAlert;

    public $name, $email, $phone, $company_name, $description;
    public int $country_id;
    public $countries, $provider, $company_id, $nationality_id, $type, $network_status, $status,$provider_id_num,$company;


    public function mount(int $provider_id, FetchSingleProviderAction $providerAction): void
    {
        $this->provider = $providerAction->handle($provider_id);
        $this->email = $this->provider?->email ?? '---';
        $this->name = $this->provider?->name;
        $this->phone = $this->provider?->phone;
        $this->country_id = $this->provider?->country_id;
        $this->company_id = $this->provider?->company_id;
        $this->nationality_id = $this->provider?->nationality_id;
        $this->provider_id_num = $this->provider?->provider_id_num;

        $this->type = $this->provider?->type;
        $this->network_status = $this->provider?->network_status;
        $this->status = $this->provider?->status;
        $this->countries = Country::all();
    }


    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:companies,email,' . $this->provider->id,
            'phone' => 'required|unique:companies,phone,' . $this->provider->id,
            'company_name' => 'required|string',
            'name' => 'required|string|min:6',
            'country_id' => 'required|numeric|exists:countries,id',
            'description' => 'nullable|sometimes|min:10',
        ];
    }

    public function updateBasicInfo(UpdateCompanyInfoAction $updateCompanyInfoAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateCompanyInfoAction->handle($this->provider->id, $data);
        $this->alert('success', 'Company Info Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.provider.tabs.info.info');
    }
}
