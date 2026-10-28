<?php

namespace Application\Company\Livewire\Driver\Tabs;

use App\Domain\Company\Actions\Dashboard\Driver\FetchSingleDriverAction;
use App\Domain\Company\Actions\Dashboard\Driver\UpdateDriverFilesAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Files extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $license_copy, $car_copy, $residence_permit_copy, $iban_copy, $car_registration_copy;
    public $license_copy_original_name, $car_copy_original_name, $residence_permit_copy_original_name, $iban_copy_original_name, $car_registration_copy_original_name;
    public $license_copy_file, $car_copy_file, $residence_permit_copy_file, $iban_copy_file, $car_registration_copy_file;
    public $license_copy_obj, $car_copy_obj, $residence_permit_copy_obj, $iban_copy_obj, $car_registration_copy_obj;
    public $driver, $diskName = 'fileDisk';

    public function mount($id, FetchSingleDriverAction $fetchSingleDriverAction)
    {
        $this->driver = $fetchSingleDriverAction->handle($id);
        $this->fillData($this->driver);
    }

    protected function rules(): array
    {
        return [
            'license_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'car_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'residence_permit_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'iban_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'car_registration_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
        ];
    }

    public function updateFiles(UpdateDriverFilesAction $updateDriverFilesAction): void
    {
        $data['files'] = $this->validate();
        DB::beginTransaction();
        $driver = $updateDriverFilesAction->handle($this->driver->id ,$data);
        $this->fillData($driver);
        $this->alert('success', __('company::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($driver): void
    {

        $this->license_copy_obj = $driver
            ->files()
            ->where(["collection_name" => 'license_copy', 'fileable_id' => $this->driver->id])
            ?->first();

        $this->license_copy_original_name = $this->license_copy_obj?->original_name;

        $this->car_copy_obj = $driver
            ->files()
            ->where(["collection_name" => 'car_copy', 'fileable_id' => $this->driver->id])
            ?->first();

        $this->car_copy_original_name = $this->car_copy_obj?->original_name;

        $this->residence_permit_copy_obj = $driver
            ->files()
            ->where(["collection_name" => 'residence_permit_copy', 'fileable_id' => $this->driver->id])
            ?->first();

        $this->residence_permit_copy_original_name = $this->residence_permit_copy_obj?->original_name;

        $this->iban_copy_obj = $driver
            ->files()
            ->where(["collection_name" => 'iban_copy', 'fileable_id' => $this->driver->id])
            ?->first();

        $this->iban_copy_original_name = $this->iban_copy_obj?->original_name;

        $this->car_registration_copy_obj = $driver
            ->files()
            ->where(["collection_name" => 'car_registration_copy', 'fileable_id' => $this->driver->id])
            ?->first();

        $this->car_registration_copy_original_name = $this->car_registration_copy_obj?->original_name;


        if(in_array(substr($this->license_copy_original_name, strpos($this->license_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            $this->license_copy_file = $this->license_copy_obj?->path ? Storage::disk($this->diskName)->url($this->license_copy_obj?->path. '/' . $this->license_copy_obj?->file_name) : null;
        }else{
            $this->license_copy_file = $this->license_copy_obj?->path ? Storage::disk($this->diskName)->url($this->license_copy_obj?->path) : null;
        }
        if(in_array(substr($this->car_copy_original_name, strpos($this->car_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            $this->car_copy_file = $this->car_copy_obj?->path ? Storage::disk($this->diskName)->url($this->car_copy_obj?->path. '/' . $this->car_copy_obj?->file_name) : null;
        }else{
            $this->car_copy_file = $this->car_copy_obj?->path ? Storage::disk($this->diskName)->url($this->car_copy_obj?->path) : null;
        }

        if(in_array(substr($this->residence_permit_copy_original_name, strpos($this->residence_permit_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            $this->residence_permit_copy_file = $this->residence_permit_copy_obj?->path ? Storage::disk($this->diskName)->url($this->residence_permit_copy_obj?->path. '/' . $this->residence_permit_copy_obj?->file_name) : null;
        }else{
            $this->residence_permit_copy_file = $this->residence_permit_copy_obj?->path ? Storage::disk($this->diskName)->url($this->residence_permit_copy_obj?->path) : null;
        }

        if(in_array(substr($this->iban_copy_original_name, strpos($this->iban_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            $this->iban_copy_file = $this->iban_copy_obj?->path ? Storage::disk($this->diskName)->url($this->iban_copy_obj?->path. '/' . $this->iban_copy_obj?->file_name) : null;
        }else{
            $this->iban_copy_file = $this->iban_copy_obj?->path ? Storage::disk($this->diskName)->url($this->iban_copy_obj?->path) : null;
        }

        if(in_array(substr($this->car_registration_copy_original_name, strpos($this->car_registration_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            $this->car_registration_copy_file = $this->car_registration_copy_obj?->path ? Storage::disk($this->diskName)->url($this->car_registration_copy_obj?->path. '/' . $this->car_registration_copy_obj?->file_name) : null;
        }else{
            $this->car_registration_copy_file = $this->car_registration_copy_obj?->path ? Storage::disk($this->diskName)->url($this->car_registration_copy_obj?->path) : null;
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.driver.tabs.files');
    }
}
