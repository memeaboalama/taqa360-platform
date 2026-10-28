<?php

namespace App\Application\Educator\Enums;

enum DocumentStatusEnum: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::VERIFIED => 'bg-success',
            self::REJECTED => 'bg-danger',
        };
    }

    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.document_status.pending'),
            self::VERIFIED => __('lang.document_status.verified'),
            self::REJECTED => __('lang.document_status.rejected'),
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isApproved(): bool
    {
        return $this === self::VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }
}
