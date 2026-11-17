<?php

namespace App\Application\Admin\Controllers\Api;

use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\AppBase\Resource\AdminNotificationResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTraitV2;

    private const VIEW_PERMISSIONS = [
        'show_notifications',
        'send_notifications',
        'send notifications_general',
    ];

    public function index(Request $request): JsonResponse
    {
        $admin = $request->user();
        if (! $this->canViewNotifications($admin)) {
            return $this->responseError(code: 403, msg: 'You are not allowed to view notifications.');
        }

        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        $notifications = NotificationNew::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($validated['per_page'] ?? 10);

        return $this->responseData(
            data: [
                'notifications' => AdminNotificationResource::collection($notifications),
            ],
            msg: 'Notifications retrieved successfully.'
        );
    }

    private function canViewNotifications(mixed $admin): bool
    {
        if (! is_object($admin)) {
            return false;
        }

        if (($admin->type ?? null) === 'admin') {
            return true;
        }

        if (($admin->type ?? null) !== 'employee' || ! method_exists($admin, 'hasAnyPermission')) {
            return false;
        }

        setPermissionsTeamId(1);

        return $admin->hasAnyPermission(self::VIEW_PERMISSIONS);
    }
}
