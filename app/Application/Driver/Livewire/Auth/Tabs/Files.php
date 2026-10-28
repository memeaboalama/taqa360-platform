<?php

namespace Application\Driver\Livewire\Auth\Tabs;

use App\Domain\Provider\Actions\Dashboard\Auth\UpdateRegisterDriveFilesAction;
use App\Domain\Provider\Actions\Dashboard\Auth\FetchSingleDriversRegistersAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

class Files extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $license_copy, $car_copy, $residence_permit_copy, $iban_copy, $car_registration_copy;
    public $license_copy_original_name, $car_copy_original_name, $residence_permit_copy_original_name, $iban_copy_original_name, $car_registration_copy_original_name;
    public $license_copy_file, $car_copy_file, $residence_permit_copy_file, $iban_copy_file, $car_registration_copy_file;
    public $license_copy_obj, $car_copy_obj, $residence_permit_copy_obj, $iban_copy_obj, $car_registration_copy_obj;
    public $driver, $diskName = 'fileDisk';

    public function mount($id, FetchSingleDriversRegistersAction $fetchSingleDriverAction)
    {
        $this->driver = $fetchSingleDriverAction->handle($id);
        Log::debug($this->driver);
        Log::debug($id);
        $this->fillData($this->driver);
    }

    protected function rules(): array
    {
        return [
            'license_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'car_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'residence_permit_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'iban_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'car_registration_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
        ];
    }

    public function updateFiles(UpdateRegisterDriveFilesAction $updateDriverFilesAction)
    {
        $data['files'] = $this->validate();
        DB::beginTransaction();
        $driver = $updateDriverFilesAction->handle($this->driver->id ,$data);
        // Reset temp file properties so Livewire doesn't re-validate expired temp files
        $this->reset(['license_copy', 'car_copy', 'residence_permit_copy', 'iban_copy', 'car_registration_copy']);
        $this->fillData($driver);
        // $this->alert('success', 'Files Updated Successfully');
        DB::commit();
        return redirect(RouteServiceProvider::DRIVER_WELCOME );
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


        // Helper: build file URL safely (path already contains filename)
        $fileUrl = function ($obj) {
            if (!$obj) {
                return null;
            }
            return $obj->url ?: ($obj->path ? Storage::disk($this->diskName)->url($obj->path) : null);
        };

        $this->license_copy_file          = $fileUrl($this->license_copy_obj);
        $this->car_copy_file              = $fileUrl($this->car_copy_obj);
        $this->residence_permit_copy_file = $fileUrl($this->residence_permit_copy_obj);
        $this->iban_copy_file             = $fileUrl($this->iban_copy_obj);
        $this->car_registration_copy_file = $fileUrl($this->car_registration_copy_obj);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.tabs.files');
    }
}
