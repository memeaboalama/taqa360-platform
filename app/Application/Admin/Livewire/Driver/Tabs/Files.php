<?php

namespace Application\Admin\Livewire\Driver\Tabs;

use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverAction;
use App\Domain\Admin\Actions\Dashboard\Driver\UpdateDriverFilesAction;
use App\Support\Traits\Alerts\WithLocalizedAlerts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Files extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    use WithLocalizedAlerts;

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

    public function updateFiles(UpdateDriverFilesAction $updateDriverFilesAction)
    {
        $data['files'] = $this->validate();
        DB::beginTransaction();
        try {
            $updateDriverFilesAction->handle($this->driver->id ,$data);
            DB::commit();
            $this->localizedAlert('success', __('admin::home.data_saved_successfully'), ['timer' => 3000]);
            $this->dispatchBrowserEvent('redirect-after-alert', ['url' => url('admin/drivers')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->localizedAlert('error', __('admin::home.something_went_wrong'));
        }
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


        $this->license_copy_file = $this->resolveFileUrl($this->license_copy_obj);
        $this->car_copy_file = $this->resolveFileUrl($this->car_copy_obj);
        $this->residence_permit_copy_file = $this->resolveFileUrl($this->residence_permit_copy_obj);
        $this->iban_copy_file = $this->resolveFileUrl($this->iban_copy_obj);
        $this->car_registration_copy_file = $this->resolveFileUrl($this->car_registration_copy_obj);
    }

    private function isDocument(?string $originalName): bool
    {
        if (!$originalName) {
            return false;
        }
        return in_array(strtolower(pathinfo($originalName, PATHINFO_EXTENSION)), ['pdf', 'csv', 'xls', 'xlsx'], true);
    }

    private function resolveFileUrl($fileObj): ?string
    {
        if (!$fileObj?->path) {
            return null;
        }
        if ($this->isDocument($fileObj->original_name ?? null)) {
            return Storage::disk($this->diskName)->url($fileObj->path . '/' . $fileObj->file_name);
        }
        return Storage::disk($this->diskName)->url($fileObj->path);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.tabs.files');
    }
}
