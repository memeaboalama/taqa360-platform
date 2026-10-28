<?php

namespace App\Application\Project\Enums;

enum AssignmentType: string
{
    case INVITATION = 'invitation';
    case DIRECT = 'direct';
    case FROM_BID = 'from_bid';

    public function label(): string
    {
        return match($this) {
            self::INVITATION => 'دعوة',
            self::DIRECT => 'تعيين مباشر',
            self::FROM_BID => 'من عطاء',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::INVITATION => 'تم دعوة المختص للمشاركة في المهمة',
            self::DIRECT => 'تم تعيين المختص مباشرة للمهمة',
            self::FROM_BID => 'تم اختيار المختص من خلال عطائه',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function defaultStatus()
    {
        return self::INVITATION;
    }
}
