<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\Admin\Actions\Dashboard\Merchant\Iban\AcceptIbanChangesAction;
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
    public $merchant;
    public $current_bank_name = "", $current_beneficiary_full_name = "", $current_iban = "";
    public $current_iban_file_path;

    public function mount($merchant_id, FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant = $fetchSingleMerchantAction->handle($merchant_id);
        $this->fillData();
    }

    public function acceptChanges(AcceptIbanChangesAction $acceptIbanChangesAction): void
    {
        $data = $this->validate([
            'iban' => ['required', $this->uniqueIbanRule()],
            'bank_name' => 'required|string',
            'beneficiary_full_name' => 'required|string|min:6',
        ]);
        DB::beginTransaction();
        $acceptIbanChangesAction->handle($this->merchant->id ,$data);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        $this->fillData();
        DB::commit();
    }

    public function updateInfo(AcceptIbanChangesAction $acceptIbanChangesAction): void
    {
        $data = $this->validate([
            'current_iban' => ['required', $this->uniqueIbanRule()],
            'current_bank_name' => 'required|string',
            'current_beneficiary_full_name' => 'required|string|min:6',
            'iban_file' => 'sometimes|mimes:jpeg,jpg,png,gif,pdf,xls,xlsx,csv|max:1024',
        ]);
        if (!$this->current_iban_file_path && !$this->iban_file) {
            $this->addError('iban_file', __('admin::messages.IMAGE_REQUIRED'));
            return;
        }
        DB::beginTransaction();
        $acceptIbanChangesAction->update($this->merchant->id ,$data);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        $this->reset('iban_file');
        $this->fillData();
        DB::commit();
    }

    public function fillData(): void
    {
        $this->merchantIban = $this->merchant->iban()->where('is_temp', 0)->first();
        $this->current_bank_name = $this->merchantIban?->bank_name;
        $this->current_beneficiary_full_name = $this->merchantIban?->beneficiary_full_name;
        $this->current_iban = $this->merchantIban?->iban;
        $this->current_iban_file_path = $this->merchantIban?->iban_file_path;
        $this->merchantTempIban = $this->merchant->iban()->where('is_temp', 1)->first();
        $this->bank_name = $this->merchantTempIban?->bank_name;
        $this->beneficiary_full_name = $this->merchantTempIban?->beneficiary_full_name;
        $this->iban = $this->merchantTempIban?->iban;
        $this->iban_file_path = $this->merchantTempIban?->iban_file_path;
    }

    private function uniqueIbanRule(): \Illuminate\Validation\Rules\Unique
    {
        $existingIbanIds = array_filter([
            $this->merchantIban?->id,
            $this->merchantTempIban?->id,
        ]);

        return Rule::unique('merchant_ibans', 'iban')->whereNotIn('id', $existingIbanIds);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.info.iban');
    }
}
