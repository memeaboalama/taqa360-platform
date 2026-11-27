<?php

namespace App\Domain\MarketPlace\Notifications;

use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BankAccountDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly MerchantBankAccountRequest $request,
        public readonly string $decision
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isApproved = $this->decision === MerchantBankAccountRequest::STATUS_APPROVED;

        return [
            'type' => 'bank_account_decision',
            'decision' => $this->decision,
            'request_id' => $this->request->id,
            'request_number' => $this->request->request_number,
            'bank_name' => $this->request->bank_name,
            'masked_iban' => $this->request->masked_iban,
            'rejection_reason' => $isApproved ? null : $this->request->rejection_reason,
            'title' => $isApproved
                ? __('marketplace::home.bank_account_approved_title')
                : __('marketplace::home.bank_account_rejected_title'),
            'message' => $isApproved
                ? __('marketplace::home.bank_account_approved_body', ['number' => $this->request->request_number])
                : __('marketplace::home.bank_account_rejected_body', [
                    'number' => $this->request->request_number,
                    'reason' => $this->request->rejection_reason,
                ]),
            'url' => route('marketplace:profile') . '#iban-tab-pane',
        ];
    }
}
