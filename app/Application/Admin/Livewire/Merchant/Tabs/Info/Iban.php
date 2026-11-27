<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Info;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use Domain\MarketPlace\Models\Merchant;
use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use App\Domain\MarketPlace\Services\BankAccountRequestService;
use App\Support\Rules\ValidSaudiIban;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Iban extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $merchant_id;
    public ?Merchant $merchant = null;
    public $approvedIban = null;
    public $pendingRequest = null;
    public $requestHistory = [];

    // Admin create on behalf of merchant
    public bool $showAdminCreateForm = false;
    public string $admin_bank_name = '';
    public string $admin_beneficiary_full_name = '';
    public string $admin_iban = '';
    public $admin_iban_file = null;
    public string $admin_reason = '';

    public function mount($merchant_id, FetchSingleMerchantAction $fetchSingleMerchantAction): void
    {
        $this->merchant_id = $merchant_id;
        $this->merchant = $fetchSingleMerchantAction->handle($merchant_id);
        $this->loadState();
    }

    public function loadState(): void
    {
        if (!$this->merchant) {
            return;
        }

        $this->merchant->refresh();
        $this->approvedIban = $this->merchant->approvedIban()->first();
        $this->pendingRequest = $this->merchant->pendingBankAccountRequest()->first();
        $this->requestHistory = $this->merchant->bankAccountRequests()->limit(15)->get();
    }

    public function openAdminCreateForm(): void
    {
        $this->reset(['admin_bank_name', 'admin_beneficiary_full_name', 'admin_iban', 'admin_iban_file', 'admin_reason']);
        $this->resetErrorBag();
        $this->showAdminCreateForm = true;
    }

    public function closeAdminCreateForm(): void
    {
        $this->showAdminCreateForm = false;
        $this->reset(['admin_bank_name', 'admin_beneficiary_full_name', 'admin_iban', 'admin_iban_file', 'admin_reason']);
        $this->resetErrorBag();
    }

    public function submitOnBehalf(BankAccountRequestService $service): void
    {
        $this->admin_iban = ValidSaudiIban::normalize($this->admin_iban);

        $this->validate([
            'admin_bank_name' => 'required|string|min:2|max:190',
            'admin_beneficiary_full_name' => 'required|string|min:6|max:190',
            'admin_iban' => ['required', 'string', new ValidSaudiIban()],
            'admin_iban_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'admin_reason' => 'required|string|min:5|max:1000',
        ], [], [
            'admin_bank_name' => __('admin::home.bank_name'),
            'admin_beneficiary_full_name' => __('admin::home.beneficiary_full_name'),
            'admin_iban' => __('admin::home.iban'),
            'admin_iban_file' => __('marketplace::home.Iban File'),
            'admin_reason' => __('admin::home.reason_for_action'),
        ]);

        try {
            $adminId = (int) auth('admin')->id();
            $service->submitRequest(
                merchantId: $this->merchant->id,
                data: [
                    'bank_name' => $this->admin_bank_name,
                    'beneficiary_full_name' => $this->admin_beneficiary_full_name,
                    'iban' => $this->admin_iban,
                    'change_reason' => $this->admin_reason,
                ],
                certificateFile: $this->admin_iban_file,
                actorType: 'admin',
                actorId: $adminId
            );

            $this->alert('success', __('admin::home.bank_request_created_by_admin_success'));
            $this->closeAdminCreateForm();
            $this->loadState();
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            $this->alert('error', collect($e->errors())->flatten()->first() ?? __('admin::messages.error'));
        }
    }

    public function render()
    {
        return view('admin::livewire.merchant.tabs.info.iban');
    }
}
