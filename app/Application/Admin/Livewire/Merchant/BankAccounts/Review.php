<?php

namespace Application\Admin\Livewire\Merchant\BankAccounts;

use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use App\Domain\MarketPlace\Services\BankAccountRequestService;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Review extends Component
{
    use LivewireAlert;

    public int $requestId;
    public ?MerchantBankAccountRequest $bankRequest = null;

    public bool $document_verified = false;
    public string $rejection_reason = '';
    public bool $showRejectBox = false;

    public function mount(int $requestId): void
    {
        $this->requestId = $requestId;
        $this->loadRequest();
    }

    public function loadRequest(): void
    {
        $this->bankRequest = MerchantBankAccountRequest::with([
            'merchant',
            'previousIban',
            'reviewer',
            'logs',
        ])->findOrFail($this->requestId);
    }

    public function toggleRejectBox(): void
    {
        $this->showRejectBox = !$this->showRejectBox;
        $this->resetErrorBag('rejection_reason');
    }

    public function approve(BankAccountRequestService $service): void
    {
        if (!$this->document_verified) {
            $this->addError('document_verified', __('admin::home.confirm_iban_document_match_required'));
            return;
        }

        try {
            $adminId = (int) auth('admin')->id();
            $service->approveRequest($this->requestId, $adminId, $this->document_verified);
            $this->alert('success', __('admin::home.bank_account_request_approved'));
            $this->loadRequest();
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            $this->alert('error', collect($e->errors())->flatten()->first() ?? __('admin::messages.error'));
        }
    }

    public function reject(BankAccountRequestService $service): void
    {
        $this->validate([
            'rejection_reason' => 'required|string|min:5|max:1000',
        ], [], [
            'rejection_reason' => __('admin::home.rejection_reason'),
        ]);

        try {
            $adminId = (int) auth('admin')->id();
            $service->rejectRequest($this->requestId, $adminId, $this->rejection_reason);
            $this->alert('success', __('admin::home.bank_account_request_rejected'));
            $this->showRejectBox = false;
            $this->loadRequest();
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            $this->alert('error', collect($e->errors())->flatten()->first() ?? __('admin::messages.error'));
        }
    }

    public function render()
    {
        return view('admin::livewire.merchant.bank-accounts.review');
    }
}
