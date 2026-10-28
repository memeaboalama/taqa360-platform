<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info;

use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateBasicInfoAction;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Info extends Component
{

    use LivewireAlert;
    use WithFileUploads;

    public  $user_name, $email, $phone, $tax_number;
    public int $country_id;
    public $city_id = 1, $region_id =1 ,$distract_id = null;
    public  $merchant, $marketplaceCategories, $mainCategories;
    public mixed $counties = [],$regions = [] , $districts = [], $cities = [], $cropImageInfo = [];
    public $marketplace_category_id = null;
    public $app_logo = "", $app_logo_path, $diskName = 'fileDisk';
    public $main_category_ids = [];
    public $company_name = ['en' => null,  'ar' => null];
    public $description = ['en' => null,  'ar' => null];

    protected $listeners = ['imageUpload' => 'imageCropped'];

    public function mount(int $merchant_id, FetchCountriesAction $fetchCountriesAction): void
    {
        $this->merchant = Merchant::findOrFail($merchant_id);
        $this->fillData($this->merchant);

        $this->counties = $fetchCountriesAction->handle();
        $this->emit('renderSelect');
        $this->marketplaceCategories = MarketPlaceCategory::where('status', StatusEnum::ACTIVE->value)->get();
        $this->mainCategories = MainCategory::where('status', StatusEnum::ACTIVE->value)->pluck('title', 'id')->toArray();
    }

    public function imageCropped($image_64, $fileInfo)
    {
        if ($this->app_logo_path) {
            $oldPath = str_replace("/storage/", "", $this->app_logo_path);
            if (Storage::disk($this->diskName)->exists($oldPath)) {
                Storage::disk($this->diskName)->delete($oldPath);
            }
        }
        
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_64));
        $imageName = "app_logo_" . Str::random(50) . ".png";
        $path = "upload/file/$imageName";
        $url = Storage::disk($this->diskName)->put($path, $imageData) ? Storage::disk($this->diskName)->url($path) : null;

        $this->cropImageInfo = [
            'original_name' => $fileInfo['filename'],
            'file_name'     => $imageName,
            'path'          => $path,
            'url'           => $url,
            'storage'       => $this->diskName,
            'type'          => 'logo',
            'size'          => $fileInfo['size'],
            'mime_type'     => 'png',
            'order_column'  => null,
        ];
        $this->app_logo_path = $url;
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:merchants,email,' . $this->merchant->id,
            'phone' => 'required|unique:merchants,phone,' . $this->merchant->id,
            'company_name.en' => 'required|string',
            'company_name.ar' => 'required|string',
            'tax_number' => 'required|string',
            'user_name' => 'required|string|min:6',
            'country_id' => 'required|numeric|exists:countries,id',
            'region_id' => 'nullable|int|min:1',
            'city_id' => 'nullable|int',
            'distract_id' => 'nullable',
            'description.en' => 'nullable|string|min:2|max:200',
            'description.ar' => 'nullable|string|min:2|max:200',
            'marketplace_category_id' => 'required|numeric|exists:market_place_categories,id',
            'main_category_ids' => 'nullable|array',
            'main_category_ids.*' => 'nullable|integer',
            'app_logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function updateBasicInfo(UpdateBasicInfoAction $updateBasicInfoAction): void
    {
        $data = $this->validate();
        if($this->app_logo_path == null && $data['app_logo'] == null)
        {
            $this->addError('app_logo', __('marketplace::messages.LOGO_REQUIRED'));
            return;
        }
        DB::beginTransaction();
        try {
            $merchant = $updateBasicInfoAction->handle($this->merchant->id ,$data,$this->cropImageInfo);
            $this->fillData($merchant);
            $this->alert('success', __('admin::lang.Basic Info Updated Successfully'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', $e->getMessage());
        }
    }

    protected function fillData($merchant) : void
    {
        $this->email = $merchant?->email;
        $this->country_id = $merchant?->country_id;
        $this->phone = $merchant?->phone;
        $this->company_name = $merchant->getTranslations('company_name');
        $this->user_name = $merchant?->user_name;
        $this->description = $merchant?->getTranslations('description');;
        $this->tax_number = $merchant?->tax_number;
        $this->app_logo_path = $merchant?->app_logo_path;
        $this->marketplace_category_id = $merchant?->categories()?->first()?->id;
        $this->main_category_ids = ($merchant?->main_categories) ? explode(',', $merchant?->main_categories) : [];
        $this->countries = Country::all();
        $this->regions = Region::where('country_id', $this->country_id)->get();
        $this->region_id = $this->merchant?->region_id;
        $this->cities = City::get();
        $this->city_id = $merchant->city_id;
        $this->districts = Distract::where('city_id', $merchant->city_id)->get();
        $this->distract_id = $merchant->distract_id;
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
        $this->reset('distract_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();

    }

    public function updateDistrict($distract_id): void
    {
        $this->distract_id = $distract_id;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.info.info');
    }
}
