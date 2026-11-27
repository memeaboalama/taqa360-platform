<?php

namespace Application\Marketplace\Livewire\Profile\Tabs;

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

    public ?Merchant $merchant = null;
    public $approvedIban = null;
    public $pendingRequest = null;
    public $latestRejectedRequest = null;
    public $requestHistory = [];

    public string $bank_name = '';
    public string $beneficiary_full_name = '';
    public string $iban = '';
    public $iban_file = null;
    public string $change_reason = '';
    public ?int $parent_request_id = null;

    public bool $showForm = false;
    public bool $showPreview = false;

    public function mount(FetchSingleMerchantAction $fetchSingleMerchantAction): void
    {
        $this->merchant = $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
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
        $this->requestHistory = $this->merchant->bankAccountRequests()->limit(20)->get();

        $latest = $this->requestHistory->first();
        $this->latestRejectedRequest = ($latest && $latest->status === MerchantBankAccountRequest::STATUS_REJECTED)
            ? $latest
            : null;
    }

    public function openCreateForm(): void
    {
        if ($this->pendingRequest) {
            $this->alert('warning', __('marketplace::home.pending_request_already_exists'), ['position' => $this->alertPosition()]);
            return;
        }

        $this->resetFormFields();
        $this->showForm = true;
        $this->showPreview = false;
    }

    public function openResubmitForm(int $rejectedRequestId): void
    {
        if ($this->pendingRequest) {
            $this->alert('warning', __('marketplace::home.pending_request_already_exists'), ['position' => $this->alertPosition()]);
            return;
        }

        $rejected = MerchantBankAccountRequest::whereKey($rejectedRequestId)
            ->where('merchant_id', $this->merchant->id)
            ->where('status', MerchantBankAccountRequest::STATUS_REJECTED)
            ->first();

        if (!$rejected) {
            return;
        }

        $this->resetFormFields();
        $this->parent_request_id = $rejected->id;
        $this->bank_name = (string) $rejected->bank_name;
        $this->beneficiary_full_name = (string) $rejected->beneficiary_full_name;
        $this->iban = (string) $rejected->iban;
        $this->change_reason = (string) ($rejected->change_reason ?? '');
        $this->showForm = true;
        $this->showPreview = false;
    }

    public function closeForm(): void
    {
        $this->resetFormFields();
        $this->showForm = false;
        $this->showPreview = false;
    }

    public function backToEdit(): void
    {
        $this->showPreview = false;
    }

    protected function rules(): array
    {
        $rules = [
            'bank_name' => 'required|string|min:2|max:190',
            'beneficiary_full_name' => 'required|string|min:6|max:190',
            'iban' => ['required', 'string', new ValidSaudiIban()],
            'iban_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ];

        if ($this->approvedIban) {
            $rules['change_reason'] = 'required|string|min:5|max:1000';
        } else {
            $rules['change_reason'] = 'nullable|string|max:1000';
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        return [
            'iban_file' => trans('marketplace::home.Iban File'),
            'iban' => trans('marketplace::home.iban'),
            'bank_name' => trans('marketplace::home.bank_name'),
            'beneficiary_full_name' => trans('marketplace::home.beneficiary_full_name'),
            'change_reason' => trans('marketplace::home.change_reason'),
        ];
    }

    public function reviewPreview(): void
    {
        $this->iban = ValidSaudiIban::normalize($this->iban);
        $this->validate();
        $this->showPreview = true;
    }

    public function submitRequest(BankAccountRequestService $service): void
    {
        $this->iban = ValidSaudiIban::normalize($this->iban);
        $validated = $this->validate();

        try {
            $service->submitRequest(
                merchantId: $this->merchant->id,
                data: $validated,
                certificateFile: $this->iban_file,
                actorType: 'merchant',
                actorId: $this->merchant->id,
                parentRequestId: $this->parent_request_id
            );

            $this->alert('success', __('marketplace::home.bank_request_submitted_successfully'), [
                'position' => $this->alertPosition(),
            ]);

            $this->closeForm();
            $this->loadState();
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            $this->showPreview = false;
            $this->alert('error', collect($e->errors())->flatten()->first() ?? __('admin::messages.error'), [
                'position' => $this->alertPosition(),
            ]);
        }
    }

    public function cancelPendingRequest(int $requestId, BankAccountRequestService $service): void
    {
        try {
            $service->cancelRequest($requestId, $this->merchant->id);
            $this->alert('success', __('marketplace::home.bank_request_cancelled_successfully'), [
                'position' => $this->alertPosition(),
            ]);
            $this->loadState();
        } catch (ValidationException $e) {
            $this->alert('error', collect($e->errors())->flatten()->first() ?? __('admin::messages.error'), [
                'position' => $this->alertPosition(),
            ]);
        }
    }

    private function resetFormFields(): void
    {
        $this->reset(['bank_name', 'beneficiary_full_name', 'iban', 'iban_file', 'change_reason', 'parent_request_id']);
        $this->resetErrorBag();
    }

    private function alertPosition(): string
    {
        return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar'
            ? 'top-start'
            : 'top-end';
    }

    public function render()
    {
        return view('marketplace::livewire.profile.tabs.iban');
    }
}
