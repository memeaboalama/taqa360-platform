<?php


namespace App\Support\Enum;

enum FileTypesEnum: string
{
    case PROFILE = 'profile';
    case LOGO = 'logo';
    case LicenseImage = 'license_image';
    case CarImage = 'car_image';
    case IdImage = 'id_image';
    case IDENTITY = 'identity';
    case ACADEMIC_CERTIFICATE = 'academic_certificate';
    case ACHIEVEMENT_ATTACHMENT = 'achievement_attachment';
    case PROFILE_IMAGE = 'profile_image';
    case BANNER = 'banner';
    case VIDEO = 'video';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

}
