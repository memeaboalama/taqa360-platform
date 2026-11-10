<?php

namespace App\Application\Project\Enums;

enum ProjectTab: string
{
    case ALL = 'all';
    case WITH_OFFERS = 'with_offers';
    case WITHOUT_OFFERS = 'without_offers';
    case PENDING_REVIEW = 'pending_review';
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
