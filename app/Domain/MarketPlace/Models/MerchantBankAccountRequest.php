<?php

namespace App\Domain\MarketPlace\Models;

use App\Domain\Admin\Models\Admin;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantBankAccountRequest extends Model
{
    use HasFactory, BelongsToMerchant, MerchantRelation;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_REVIEW,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'request_number',
        'merchant_id',
        'previous_iban_id',
        'parent_request_id',
        'bank_name',
        'beneficiary_full_name',
        'iban',
        'iban_file',
        'iban_file_disk',
        'iban_file_mime',
        'change_reason',
        'status',
        'submitted_by_type',
        'submitted_by_id',
        'submitted_at',
        'reviewed_by_admin_id',
        'reviewed_at',
        'rejection_reason',
        'document_verified',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'document_verified' => 'boolean',
    ];

    public function previousIban(): BelongsTo
    {
        return $this->belongsTo(MerchantIban::class, 'previous_iban_id');
    }

    public function parentRequest(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_request_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MerchantBankAccountLog::class, 'request_id')->orderByDesc('created_at')->orderByDesc('id');
    }

    public function getMaskedIbanAttribute(): string
    {
        return self::maskIban($this->iban);
    }

    public static function maskIban(?string $iban): string
    {
        $clean = strtoupper(preg_replace('/\s+/', '', (string) $iban));
        if ($clean === '') {
            return '';
        }

        $len = strlen($clean);
        if ($len <= 4) {
            return $clean;
        }

        $prefix = substr($clean, 0, 2);
        $last4 = substr($clean, -4);
        $maskedMiddle = str_repeat('*', max(0, $len - 6));

        return trim(chunk_split($prefix . $maskedMiddle . $last4, 4, ' '));
    }

    public function isPendingReview(): bool
    {
        return $this->status === self::STATUS_PENDING_REVIEW;
    }

    public function isEditableOrCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_PENDING_REVIEW], true);
    }

    public function getSubmitterNameAttribute(): string
    {
        if ($this->submitted_by_type === 'admin' && $this->submitted_by_id) {
            $admin = Admin::find($this->submitted_by_id);
            return $admin ? ($admin->name . ' (Admin)') : 'Admin #' . $this->submitted_by_id;
        }

        return $this->merchant?->company_name ?: ($this->merchant?->name ?: ('Merchant #' . $this->merchant_id));
    }
}
