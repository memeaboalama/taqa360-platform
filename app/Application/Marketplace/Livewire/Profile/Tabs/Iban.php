<?php

namespace Application\Marketplace\Livewire\Profile\Tabs;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateIbanAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Iban extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $bank_name = "", $beneficiary_full_name = "", $iban = "";
    public $iban_file = "", $iban_file_path, $diskName = 'fileDisk';
    public $merchantIban, $merchantTempIban;
    public function mount(FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant = $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
        $this->fillData($this->merchant);
    }

    public function nickNames()
    {
        return [
            'iban_file' => trans('marketplace::home.Iban File'),
            'iban' => trans('marketplace::home.iban'),
            'bank_name' => trans('marketplace::home.bank_name'),
            'beneficiary_full_name' => trans('marketplace::home.beneficiary_full_name'),
        ];
    }

    protected function rules(): array
    {
        return [
            'iban' => ['required', 'unique:merchant_ibans,id,' . $this->merchantIban?->id],
            'bank_name' => 'required|string',
            'beneficiary_full_name' => 'required|string|min:6',
            'iban_file' => 'sometimes|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:2048',
        ];
    }

    public function updateIban(UpdateIbanAction $updateIbanAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        if($this->iban_file_path == null && $data['iban_file'] == null)
        {
            $this->addError('iban_file', __('admin::messages.IMAGE_REQUIRED'));
            $this->alert('error', __('admin::messages.IMAGE_REQUIRED'), ['position' => $this->alertPosition()]);
            return;
        }
        DB::beginTransaction();
        $merchant = $updateIbanAction->handle($this->merchant->id ,$data);
        $this->alert('success', 'IBAN Info Requested to be Accepted, Please Wait!', ['position' => $this->alertPosition()]);
        $this->reset('iban','bank_name','iban_file');
        $this->fillData($merchant);
        DB::commit();
    }

    private function alertPosition(): string
    {
        return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar'
            ? 'top-start'
            : 'top-end';
    }

    public function fillData($merchant) : void
    {
        $this->merchantIban = $merchant->iban()->where('is_temp', 0)->first();
        $this->merchantTempIban = $merchant->iban()->where('is_temp', 1)->first();
        $this->temp_bank_name = $this->merchantTempIban?->bank_name;
        $this->temp_beneficiary_full_name = $this->merchantTempIban?->beneficiary_full_name;
        $this->temp_iban = $this->merchantTempIban?->iban;
        $this->temp_iban_file_path = $this->merchantTempIban?->iban_file_path;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.tabs.iban');
    }
}
