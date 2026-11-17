<?php

namespace App\Domain\Notification\Actions;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class SendIDeviceTokenToDB
{
    public function handle(Model $user, array $data)
    {
        $token = trim((string) ($data['device_token'] ?? ''));
        $platform = $data['platform'] ?? 'ios';

        if ($token === '') {
            throw new InvalidArgumentException('A non-empty device token is required.');
        }

        if (! in_array($platform, ['ios', 'android', 'web'], true)) {
            throw new InvalidArgumentException('Unsupported device platform.');
        }

        return $user->device_tokens()->updateOrCreate(
            ['token' => $token],
            ['platform' => $platform]
        );
    }
}
