<?php

namespace App\Application\Marketplace\Enums;

enum MerchantOrderHistoryStatuses: string
{
    case DRAFT = "draft";
    case PENDING = "pending";
    case MARKED_READY = 'marked-ready';
    case START_COOKING = 'start-cooking';
    case UPCOMING = 'confirmed';
    case USER_CANCELLED = 'user-cancel';
    case EMPLOYEE_ACCEPT = 'employee-accept';
    case EMPLOYEE_REJECT = 'employee-reject';
    case DRIVER_ACCEPT = 'driver-accept';
    case DRIVER_ON_WAY = 'driver-on-way';
    case WILL_BE_LATE = 'will-be-late';
    case COMPLETED = 'completed-order';
    case EMPLOYEE_COMPLETED = 'employee-completed-order';
    case EXPIRED = 'expired';
    case CREATE_ORDER = 'create-order';
    case DRIVER_ASSIGNED_MANUALLY = 'driver-assign-manually';
    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-warning',
            self::PENDING => 'bg-warning',
            self::CREATE_ORDER => 'bg-warning',
            self::START_COOKING => 'bg-secondary',
            self::EMPLOYEE_ACCEPT => 'bg-success',
            self::COMPLETED => 'bg-success',
            self::EMPLOYEE_COMPLETED => 'bg-success',
            self::DRIVER_ACCEPT => 'bg-success',
            self::EXPIRED => 'bg-danger',
            self::USER_CANCELLED => 'bg-danger',
            self::MARKED_READY => 'bg-primary',
            self::DRIVER_ON_WAY => 'bg-primary',
        };
    }


    // public function trans(): string
    // {
    //     return match ($this) {
    //         self::DRAFT => __('lang.order_status.draft'),
    //         self::PENDING => __('lang.order_status.pending'),
    //         self::START_COOKING => __('lang.order_status.START_COOKING'),
    //         self::UPCOMING => __('lang.order_status.confirmed'),
    //         self::PREVIOUS => __('lang.order_status.completed'),
    //         self::EXPIRED => __('lang.order_status.expired'),
    //         self::CANCELLED => __('lang.order_status.cancelled'),
    //         self::ON_WAY => __('lang.order_status.on_way'),
    //         self::READY => __('lang.order_status.ready'),
    //         self::WILL_BE_LATE => __('lang.order_status.will-be-late'),
    //     };
    // }

}
