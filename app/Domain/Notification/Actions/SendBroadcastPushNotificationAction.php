<?php

namespace App\Domain\Notification\Actions;

use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\MerchantEmployee;
use Domain\Notification\Models\DeviceToken;
use App\Domain\User\Models\User;
use InvalidArgumentException;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use RuntimeException;

class SendBroadcastPushNotificationAction
{
    private const AUDIENCES = [
        User::class,
        Driver::class,
        MerchantEmployee::class,
    ];

    public function __construct(private ?Messaging $messaging = null)
    {
    }

    /**
     * @return array{attempted: int, sent: int, failed: int}
     */
    public function handle(array $data): array
    {
        if (! in_array($data['device_type'] ?? null, self::AUDIENCES, true)) {
            throw new InvalidArgumentException('Unsupported notification audience.');
        }

        $tokens = DeviceToken::query()
            ->where('deviceable_type', $data['device_type'])
            ->whereNotNull('token')
            ->where('token', '!=', '')
            ->distinct()
            ->pluck('token')
            ->all();

        if ($tokens === []) {
            return ['attempted' => 0, 'sent' => 0, 'failed' => 0];
        }

        $messaging = $this->messaging ?? $this->createMessaging();
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => $data['title'],
                'body' => $data['body'],
            ],
            'data' => [
                'type' => 'admin_broadcast',
                'notification_id' => (string) ($data['notification_id'] ?? ''),
            ],
        ]);

        $sent = 0;
        $failed = 0;

        foreach (array_chunk($tokens, 500) as $tokenBatch) {
            $report = $messaging->sendMulticast($message, $tokenBatch);
            $sent += $report->successes()->count();
            $failed += $report->failures()->count();
        }

        return [
            'attempted' => count($tokens),
            'sent' => $sent,
            'failed' => $failed,
        ];
    }

    private function createMessaging(): Messaging
    {
        $credentialsPath = config('services.firebase.credentials');

        if (! is_string($credentialsPath) || $credentialsPath === '' || ! is_file($credentialsPath)) {
            throw new RuntimeException('Firebase credentials are not configured or the credential file is missing.');
        }

        return (new Factory())
            ->withServiceAccount($credentialsPath)
            ->createMessaging();
    }
}
