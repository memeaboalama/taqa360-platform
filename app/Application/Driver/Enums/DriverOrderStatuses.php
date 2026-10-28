<?php

namespace App\Application\Driver\Enums;

enum DriverOrderStatuses: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case UPCOMING = 'confirmed';
    case CANCELLED = 'cancelled';

    case CURRENT = 'started-work';
    case READY = 'ready';
    case ON_WAY = 'on-way';
    case WILL_BE_LATE = 'will-be-late';
    case PREVIOUS = 'completed';
    case EXPIRED = 'expired';
    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-purple-transparent',
            self::PENDING => 'bg-warning',
            self::CURRENT => 'bg-secondary',
            self::UPCOMING => 'bg-success',
            self::PREVIOUS => 'bg-success',
            self::EXPIRED => 'bg-danger',
            self::CANCELLED => 'bg-danger',
            self::READY => 'bg-primary',
            self::ON_WAY => 'bg-primary',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::DRAFT => __('lang.order_status.draft'),
            self::PENDING => __('lang.order_status.pending'),
            self::CURRENT => __('lang.order_status.current'),
            self::UPCOMING => __('lang.order_status.confirmed'),
            self::PREVIOUS => __('lang.order_status.completed'),
            self::EXPIRED => __('lang.order_status.expired'),
            self::CANCELLED => __('lang.order_status.cancelled'),
            self::ON_WAY => __('lang.order_status.on_way'),
            self::READY => __('lang.order_status.ready'),
            self::WILL_BE_LATE => __('lang.order_status.will-be-late'),
        };
    }
    
    public function isCompleted(): bool
    {return $this == self::PREVIOUS;}
    public function isCancelled(): bool
    {return $this == self::CANCELLED;}
}
