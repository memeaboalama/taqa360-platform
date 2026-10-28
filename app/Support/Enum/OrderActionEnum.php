<?php


namespace App\Support\Enum;

enum OrderActionEnum: string
{
    case CREATED = 'created';

    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case ON_RIDE = 'on_ride';
    case REJECTED_BY_PROVIDER = 'rejected_by_provider';
    case CANCELLED_BY_CLIENT = 'cancelled_by_client';

    case ARRIVED = 'arrived';
    case COMPLETED = 'completed';

    case TIMEOUT = 'timeout';


    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-info',
            self::ACCEPTED => 'bg-secondary',
            self::ON_RIDE => 'bg-primary',
            self::REJECTED_BY_PROVIDER, self::CANCELLED_BY_CLIENT => 'bg-danger',
            self::COMPLETED => 'bg-success',
            self::TIMEOUT => 'bg-warning',
        };
    }


    public function trans(): string
    {
        return match ($this) {
            self::PENDING => __('lang.order_status.pending'),
            self::ACCEPTED => __('lang.order_status.accepted'),
            self::ON_RIDE => __('lang.order_status.on_ride'),
            self::REJECTED_BY_PROVIDER => __('lang.order_status.rejected_by_provider'),
            self::CANCELLED_BY_CLIENT => __('lang.order_status.canceled_by_client'),
            self::COMPLETED => __('lang.order_status.completed'),
            self::TIMEOUT => __('lang.order_status.timeout'),
        };
    }
}

