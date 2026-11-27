<?php

namespace App\Domain\MarketPlace\Services;

use Domain\MarketPlace\Models\Merchant;
use App\Domain\MarketPlace\Models\MerchantBankAccountLog;
use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use App\Domain\MarketPlace\Models\MerchantIban;
use App\Domain\MarketPlace\Notifications\BankAccountDecisionNotification;
use App\Support\Rules\ValidSaudiIban;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BankAccountRequestService
{
    public const ALLOWED_MIMES = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    public const MAX_FILE_BYTES = 1048576; // 1 MB

    public function storeCertificate(UploadedFile $file, int $merchantId): array
    {
        if ($file->getSize() > self::MAX_FILE_BYTES) {
            throw ValidationException::withMessages([
                'iban_file' => [__('marketplace::home.iban_file_too_large')],
            ]);
        }

        $realPath = $file->getRealPath();
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $detectedMime = $realPath ? $finfo->file($realPath) : null;

        if (!$detectedMime || !array_key_exists($detectedMime, self::ALLOWED_MIMES)) {
            throw ValidationException::withMessages([
                'iban_file' => [__('marketplace::home.iban_file_invalid_type')],
            ]);
        }

        $ext = self::ALLOWED_MIMES[$detectedMime];
        $filename = Str::uuid()->toString() . '.' . $ext;
        $directory = 'private/merchant-iban-certificates/' . $merchantId;
        $path = $file->storeAs($directory, $filename, 'local');

        return [
            'path' => $path,
            'disk' => 'local',
            'mime' => $detectedMime,
        ];
    }

    public function submitRequest(
        int $merchantId,
        array $data,
        ?UploadedFile $certificateFile,
        string $actorType = 'merchant',
        ?int $actorId = null,
        ?int $parentRequestId = null
    ): MerchantBankAccountRequest {
        $normalizedIban = ValidSaudiIban::normalize($data['iban'] ?? '');

        if (!ValidSaudiIban::isValid($normalizedIban)) {
            throw ValidationException::withMessages([
                'iban' => [__('marketplace::home.invalid_saudi_iban')],
            ]);
        }

        return DB::transaction(function () use ($merchantId, $data, $certificateFile, $actorType, $actorId, $parentRequestId, $normalizedIban) {
            $merchant = Merchant::whereKey($merchantId)->lockForUpdate()->firstOrFail();

            $hasPending = MerchantBankAccountRequest::where('merchant_id', $merchantId)
                ->where('status', MerchantBankAccountRequest::STATUS_PENDING_REVIEW)
                ->exists();

            if ($hasPending) {
                throw ValidationException::withMessages([
                    'iban' => [__('marketplace::home.pending_request_already_exists')],
                ]);
            }

            $activeIban = $merchant->approvedIban()->first();

            // Check uniqueness against other merchants' active IBANs
            $duplicateActive = MerchantIban::where('iban', $normalizedIban)
                ->where('is_temp', 0)
                ->where('is_active', true)
                ->where('merchant_id', '!=', $merchantId)
                ->exists();

            if ($duplicateActive) {
                throw ValidationException::withMessages([
                    'iban' => [__('validation.unique', ['attribute' => __('marketplace::home.iban')])],
                ]);
            }

            $requiresReason = $activeIban !== null || $actorType === 'admin';
            $changeReason = trim((string) ($data['change_reason'] ?? ''));

            if ($requiresReason && $changeReason === '') {
                throw ValidationException::withMessages([
                    'change_reason' => [__('marketplace::home.change_reason_required')],
                ]);
            }

            $storedFile = null;
            if ($certificateFile) {
                $storedFile = $this->storeCertificate($certificateFile, $merchantId);
            } elseif (!empty($data['existing_iban_file'])) {
                $storedFile = [
                    'path' => $data['existing_iban_file'],
                    'disk' => $data['existing_iban_file_disk'] ?? 'local',
                    'mime' => $data['existing_iban_file_mime'] ?? null,
                ];
            }

            if (!$storedFile) {
                throw ValidationException::withMessages([
                    'iban_file' => [__('admin::messages.IMAGE_REQUIRED')],
                ]);
            }

            $request = MerchantBankAccountRequest::create([
                'request_number' => $this->generateRequestNumber(),
                'merchant_id' => $merchantId,
                'previous_iban_id' => $activeIban?->id,
                'parent_request_id' => $parentRequestId,
                'bank_name' => trim((string) $data['bank_name']),
                'beneficiary_full_name' => trim((string) $data['beneficiary_full_name']),
                'iban' => $normalizedIban,
                'iban_file' => $storedFile['path'],
                'iban_file_disk' => $storedFile['disk'],
                'iban_file_mime' => $storedFile['mime'],
                'change_reason' => $changeReason !== '' ? $changeReason : null,
                'status' => MerchantBankAccountRequest::STATUS_PENDING_REVIEW,
                'submitted_by_type' => $actorType,
                'submitted_by_id' => $actorId ?? $merchantId,
                'submitted_at' => now(),
            ]);

            $this->log(
                $request,
                $actorType,
                $actorId ?? $merchantId,
                $parentRequestId ? 'resubmitted' : 'submitted',
                $changeReason !== '' ? $changeReason : null,
                ['iban_last4' => substr($normalizedIban, -4)]
            );

            return $request;
        });
    }

    public function cancelRequest(int $requestId, int $merchantId): MerchantBankAccountRequest
    {
        return DB::transaction(function () use ($requestId, $merchantId) {
            $request = MerchantBankAccountRequest::whereKey($requestId)
                ->where('merchant_id', $merchantId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($request->status !== MerchantBankAccountRequest::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages([
                    'status' => [__('marketplace::home.cannot_cancel_non_pending_request')],
                ]);
            }

            $request->update([
                'status' => MerchantBankAccountRequest::STATUS_CANCELLED,
            ]);

            $this->log($request, 'merchant', $merchantId, 'cancelled');

            return $request;
        });
    }

    public function approveRequest(int $requestId, int $adminId, bool $documentVerified): MerchantBankAccountRequest
    {
        if (!$documentVerified) {
            throw ValidationException::withMessages([
                'document_verified' => [__('admin::home.confirm_iban_document_match_required')],
            ]);
        }

        return DB::transaction(function () use ($requestId, $adminId, $documentVerified) {
            $request = MerchantBankAccountRequest::whereKey($requestId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($request->status !== MerchantBankAccountRequest::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages([
                    'status' => [__('admin::home.request_already_processed')],
                ]);
            }

            // Dual-control: if an admin created this request on behalf of a merchant, a different admin must approve it.
            if ($request->submitted_by_type === 'admin' && (int) $request->submitted_by_id === $adminId) {
                throw ValidationException::withMessages([
                    'status' => [__('admin::home.dual_control_approval_required')],
                ]);
            }

            $merchant = Merchant::whereKey($request->merchant_id)->lockForUpdate()->firstOrFail();

            // Supersede any existing active IBAN without deleting history
            MerchantIban::where('merchant_id', $merchant->id)
                ->where('is_temp', 0)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'superseded_at' => now(),
                ]);

            // Remove legacy temp record if present
            MerchantIban::where('merchant_id', $merchant->id)
                ->where('is_temp', 1)
                ->delete();

            MerchantIban::create([
                'merchant_id' => $merchant->id,
                'bank_name' => $request->bank_name,
                'beneficiary_full_name' => $request->beneficiary_full_name,
                'iban' => $request->iban,
                'is_temp' => 0,
                'is_active' => true,
                'approved_at' => now(),
                'approved_by' => $adminId,
                'request_id' => $request->id,
                'iban_file' => $request->iban_file,
                'iban_file_disk' => $request->iban_file_disk,
            ]);

            $request->update([
                'status' => MerchantBankAccountRequest::STATUS_APPROVED,
                'reviewed_by_admin_id' => $adminId,
                'reviewed_at' => now(),
                'document_verified' => $documentVerified,
            ]);

            $this->log($request, 'admin', $adminId, 'approved', null, [
                'document_verified' => true,
                'iban_last4' => substr($request->iban, -4),
            ]);

            $merchant->notify(new BankAccountDecisionNotification($request->fresh(), MerchantBankAccountRequest::STATUS_APPROVED));

            return $request->fresh();
        });
    }

    public function rejectRequest(int $requestId, int $adminId, string $rejectionReason): MerchantBankAccountRequest
    {
        $reason = trim($rejectionReason);
        if ($reason === '') {
            throw ValidationException::withMessages([
                'rejection_reason' => [__('admin::home.rejection_reason_required')],
            ]);
        }

        return DB::transaction(function () use ($requestId, $adminId, $reason) {
            $request = MerchantBankAccountRequest::whereKey($requestId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($request->status !== MerchantBankAccountRequest::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages([
                    'status' => [__('admin::home.request_already_processed')],
                ]);
            }

            $request->update([
                'status' => MerchantBankAccountRequest::STATUS_REJECTED,
                'reviewed_by_admin_id' => $adminId,
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $this->log($request, 'admin', $adminId, 'rejected', $reason);

            $merchant = Merchant::find($request->merchant_id);
            if ($merchant) {
                $merchant->notify(new BankAccountDecisionNotification($request->fresh(), MerchantBankAccountRequest::STATUS_REJECTED));
            }

            return $request->fresh();
        });
    }

    private function log(
        MerchantBankAccountRequest $request,
        string $actorType,
        ?int $actorId,
        string $action,
        ?string $notes = null,
        array $metadata = []
    ): void {
        MerchantBankAccountLog::create([
            'request_id' => $request->id,
            'merchant_id' => $request->merchant_id,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'action' => $action,
            'notes' => $notes,
            'metadata' => empty($metadata) ? null : $metadata,
            'created_at' => now(),
        ]);
    }

    private function generateRequestNumber(): string
    {
        $nextId = (int) (MerchantBankAccountRequest::max('id') ?? 0) + 1;
        return 'IBAN-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
