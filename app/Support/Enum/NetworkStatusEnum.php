<?php


namespace App\Support\Enum;

enum NetworkStatusEnum: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';

    public function label(): string
    {
        return match($this) {
            self::ONLINE => __('admin::home.online'),
            self::OFFLINE => __('admin::home.offline'),
        };
    }
}
