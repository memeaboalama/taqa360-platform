<?php

namespace Application\Educator\Livewire\Profile\Builder\Steps;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Educator\Actions\Profile\UpdateBasicInfoAction;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Basic extends Component
{
    use WithFileUploads, LivewireAlert;

    public Educator $educator;
    public $name, $email, $phone;
    public $nationality_id, $gender, $country_id, $city_id;
    public $identity_type, $identity_id, $experience_years, $specialization;
    public $identityImageFront, $identityImageBack;
    public $existingIdentityImageFront = null;
    public $existingIdentityImageBack = null;
    public $existingPassportImage = null;
    public $nationalities, $countries, $cities = [];
    protected $listeners = ['executeDelete' => 'handleConfirmation'];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:educators,email,' . $this->educator->id,
            'phone' => 'required|string|unique:educators,phone,' . $this->educator->id,
            'nationality_id' => 'required|exists:nationalities,id',
            'gender' => 'required|in:male,female',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'identity_type' => 'required|in:national_id,passport',
            'identity_id' => 'required|string|max:30',
            'experience_years' => 'required|integer|min:0|max:60',
            'specialization' => 'nullable|string|max:255',
            'identityImageFront' => 'nullable|image|max:2048',
        ];

        if ($this->identity_type === 'national_id') {
            $rules['identityImageBack'] = 'nullable|image|max:2048';
        }
        return $rules;
    }

    protected function validationAttributes()
    {
        return [
            'name' => __('educator::builder.basic_info.name'),
            'email' => __('educator::builder.basic_info.email'),
            'phone' => __('educator::builder.basic_info.phone'),
            'nationality_id' => __('educator::builder.basic_info.nationality'),
            'gender' => __('educator::builder.basic_info.gender'),
            'country_id' => __('educator::builder.basic_info.country'),
            'city_id' => __('educator::builder.basic_info.city'),
            'identity_type' => __('educator::builder.basic_info.identity_type'),
            'identity_id' => __('educator::builder.basic_info.identity_id'),
            'experience_years' => __('educator::builder.basic_info.experience_years'),
            'specialization' => __('educator::builder.basic_info.specialization'),
            'identityImageFront' => __('educator::builder.basic_info.identity_image_front'),
            'identityImageBack' => __('educator::builder.basic_info.identity_image_back'),
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator->load(['profile']);
        $this->fillData($this->educator);
        $this->nationalities = Nationality::where('status', 'active')->get();
        $this->countries = Country::where('status', 'active')->get();
    }

    protected function fillData(Educator $educator): void
    {
        $this->name = $educator->name;
        $this->email = $educator->email;
        $this->phone = $educator->phone;
        if ($educator->profile) {
            $this->nationality_id = $educator->profile->nationality_id;
            $this->gender = $educator->profile->gender;
            $this->country_id = $educator->profile->country_id;
            $this->city_id = $educator->profile->city_id;
            $this->identity_type = $educator->profile->identity_type;
            $this->identity_id = $educator->profile->identity_id;
            $this->experience_years = $educator->profile->experience_years;
            $this->specialization = $educator->profile->specialization;
            if ($this->country_id) {
                $this->cities = City::where('country_id', $this->country_id)->where('status', 'active')->get();
            }
        }
        $this->existingIdentityImageFront = $educator->identityFrontImage;
        $this->existingIdentityImageBack = $educator->identityBackImage;
        $this->existingPassportImage = $educator->passportImage;
    }

    public function updatedCountryId($countryId)
    {
        $this->cities = $countryId ? City::where('country_id', $countryId)->where('status', 'active')->get() : [];
        $this->city_id = null;
    }

    public function removeTempImage($imageName)
    {
        $this->$imageName = null;
        $this->resetErrorBag($imageName);
    }

    public function confirmImageRemoval(string $collectionName)
    {
        $this->emitUp('showDeleteConfirmation', __('educator::builder.confirm_delete_title'), __('educator::builder.confirm_delete_image_text'), 'removeExistingImage', $collectionName);
    }

    public function handleConfirmation(string $method, $params)
    {
        if ($method === 'removeExistingImage') {
            $this->removeExistingImage($params);
        }
    }

    public function removeExistingImage(string $collectionName)
    {
        $imageService = resolve(ImageService::class);

        $fileRelation = match($collectionName) {
            'identity_front' => 'identityFrontImage',
            'identity_back' => 'identityBackImage',
            'passport_image' => 'passportImage',
            default => null
        };

        if ($fileRelation && $file = $this->educator->$fileRelation) {
            $imageService->destroyImg($file->path, $file->storage);
            $file->delete();
            $this->fillData($this->educator);
            $this->alert('success', __('educator::builder.messages.image_deleted_successfully'));
        }
    }

    public function save(UpdateBasicInfoAction $updateBasicInfoAction)
    {
        $validatedData = $this->validate();
        try {
            $updatedEducator = $updateBasicInfoAction->handle($this->educator, $validatedData, $this->identityImageFront, $this->identityImageBack);
            $this->fillData($updatedEducator);
            $this->identityImageFront = null;
            $this->identityImageBack = null;
            $this->alert('success', __('educator::builder.messages.success_updated', ['item' => __('educator::builder.steps.basic_info')]));
            $this->emitUp('stepCompleted', 1);
        } catch (\Exception $e) {
            Log::error("Error in Basic Info Save: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->alert('error', __('educator::builder.messages.generic_error'));
        }
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.basic');
    }
}
