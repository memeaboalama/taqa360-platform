<?php

namespace Application\Marketplace\Livewire\Profile\Tabs;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateFilesAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Files extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $owner_id,
        $owner_id_original_name,
        $company_logo,
        $company_logo_original_name,
        $national_address_copy,
        $national_address_copy_original_name,
        $menu_copy,
        $menu_copy_original_name,
        $cr_copy,
        $cr_copy_original_name,
        $signed_contract_copy ,
        $signed_contract_copy_original_name ,
        $bank_account_details,
        $bank_account_details_original_name,
        $vat_certificated,
        $vat_certificated_original_name,
        $diskName = 'fileDisk';

    public $owner_id_file,
        $company_logo_file,
        $menu_copy_file,
        $national_address_copy_file,
        $cr_copy_file,
        $bank_account_details_file,
        $vat_certificated_file,
        $signed_contract_copy_file;

    public $owner_id_obj,
        $company_logo_obj,
        $menu_copy_obj,
        $national_address_copy_obj,
        $cr_copy_obj,
        $bank_account_details_obj,
        $vat_certificated_obj,
        $signed_contract_copy_obj;

    public function mount(FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant = $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
        $this->fillData($this->merchant);
    }

    protected function rules(): array
    {
        return [
            'company_logo' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'owner_id' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'bank_account_details' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'vat_certificated' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'menu_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'cr_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'national_address_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'signed_contract_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
        ];
    }

    public function updateFiles(UpdateFilesAction $updateFilesAction): void
    {
        $data['files'] = $this->validate();
        DB::beginTransaction();
        $merchant = $updateFilesAction->handle($this->merchant->id ,$data);
        // Reset temp file properties so Livewire doesn't re-validate expired temp files on second save
        $this->reset(['company_logo', 'owner_id', 'bank_account_details', 'vat_certificated', 'menu_copy', 'cr_copy', 'national_address_copy', 'signed_contract_copy']);
        $this->fillData($merchant);
        $this->alert('success', __('marketplace::home.data_updated_successfully'), ['position' => $this->alertPosition()]);
        DB::commit();
    }

    private function alertPosition(): string
    {
        return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar'
            ? 'top-start'
            : 'top-end';
    }

    protected function fillData($merchant): void
    {

        $this->company_logo_obj = $merchant
            ->files()
            ->where(["collection_name" => 'company_logo', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->company_logo_original_name = $this->company_logo_obj?->original_name;

        $this->bank_account_details_obj = $merchant
            ->files()
            ->where(["collection_name" => 'bank_account_details', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->bank_account_details_original_name = $this->bank_account_details_obj?->original_name;

        $this->vat_certificated_obj = $merchant
            ->files()
            ->where(["collection_name" => 'vat_certificated', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->vat_certificated_original_name = $this->vat_certificated_obj?->original_name;


        $this->owner_id_obj = $merchant
            ->files()
            ->where(["collection_name" => 'owner_id', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->owner_id_original_name = $this->owner_id_obj?->original_name;

        $this->menu_copy_obj = $merchant
            ->files()
            ->where(["collection_name" => 'menu_copy', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->menu_copy_original_name = $this->menu_copy_obj?->original_name;

        $this->cr_copy_obj = $merchant
            ->files()
            ->where(["collection_name" => 'cr_copy', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->cr_copy_original_name = $this->cr_copy_obj?->original_name;

        $this->national_address_copy_obj = $merchant
            ->files()
            ->where(["collection_name" => 'national_address_copy', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->national_address_copy_original_name = $this->national_address_copy_obj?->original_name;

        $this->signed_contract_copy_obj = $merchant
            ->files()
            ->where(["collection_name" => 'signed_contract_copy', 'fileable_id' => $this->merchant->id])
            ?->first();

        $this->signed_contract_copy_original_name = $this->signed_contract_copy_obj?->original_name;

        // `path` already contains the file name (ImageService::storeImage stores
        // path as "$folder/$name"), so the URL must never append `file_name`
        // again - that produced a 404 for every pdf/csv/xls/xlsx document.
        // Prefer the url stored at upload time and fall back to the disk path.
        $fileUrl = function ($obj) {
            if (!$obj) {
                return null;
            }

            return $obj->url ?: ($obj->path ? Storage::disk($this->diskName)->url($obj->path) : null);
        };

        $this->company_logo_file          = $fileUrl($this->company_logo_obj);
        $this->bank_account_details_file  = $fileUrl($this->bank_account_details_obj);
        $this->vat_certificated_file      = $fileUrl($this->vat_certificated_obj);
        $this->owner_id_file              = $fileUrl($this->owner_id_obj);
        $this->menu_copy_file             = $fileUrl($this->menu_copy_obj);
        $this->cr_copy_file               = $fileUrl($this->cr_copy_obj);
        $this->national_address_copy_file = $fileUrl($this->national_address_copy_obj);
        $this->signed_contract_copy_file  = $fileUrl($this->signed_contract_copy_obj);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.tabs.files');
    }
}
