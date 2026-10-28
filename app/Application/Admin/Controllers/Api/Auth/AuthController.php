<?php

namespace App\Application\Admin\Controllers\Api\Auth;

use App\Application\Api\Requests\ElmApp\Auth\LoginRequest;
use App\Domain\Admin\Actions\Dashboard\Auth\DetectAdminAction;
use Domain\Admin\Models\Admin;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Sanctum token names. Kept in constants because refresh() has to be able to
     * tell a refresh token apart from an access token.
     */
    private const ACCESS_TOKEN_NAME = 'admin-api';
    private const REFRESH_TOKEN_NAME = 'admin-refresh-token';

    public function login(LoginRequest $request, DetectAdminAction $detectAdminAction, SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {

        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        $admin = $detectAdminAction->detectAdminApi($data);
        if (!$admin) return $this->errorResponse(401, __('auth.failed'));
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($admin, $request->validated());
        // config('sanctum.expiration', 1440) returns null here because the key
        // exists in config/sanctum.php with a null value, and config() only falls
        // back to its default when the key is missing. That made expires_at equal
        // to "now", so every issued token was already expired and Sanctum's guard
        // rejected it with "Unauthenticated." Use ?: so an explicit null still
        // gets the intended 24h lifetime.
        $expirationMinutes = config('sanctum.expiration') ?: 1440;
        return $this->responseData([
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin?->phone ?? 77554774433,
                'role' => "admin",
                'status' => $admin?->status,
                'avatarUrl' => $admin?->image
            ],
            'tokens' => [
                    'accessToken' => $admin->createToken(self::ACCESS_TOKEN_NAME, ['*'], now()->addMinutes($expirationMinutes))->plainTextToken,
                    'refreshToken' => $admin->createToken(self::REFRESH_TOKEN_NAME, ['*'], now()->addDays(30))->plainTextToken, // صلاحية أطول
                    'expiresAt' => Carbon::now()->addMinutes($expirationMinutes)->toIso8601String(), // أرسل تاريخ بصيغة ISO 8601
                ],
            ], 200);
    }

    /**
     * POST /api/v1/auth/refresh
     *
     * The SPA (core/api/client.ts) posts { refreshToken } here whenever a request
     * comes back 401, then swaps in the returned pair. The route used to not
     * exist, so a single 401 wiped the stored tokens and logged the user out.
     *
     * Refresh tokens are rotated: the presented one is revoked as soon as it is
     * used, so a stolen refresh token is only good until the real client
     * refreshes once.
     */
    public function refresh(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'refreshToken' => ['required', 'string'],
            'refresh_token' => ['sometimes', 'string'],
        ]);

        $plainTextToken = $validated['refreshToken'] ?? $validated['refresh_token'] ?? null;

        // Sanctum 3.x exposes findToken() on PersonalAccessToken, not on the model.
        $token = PersonalAccessToken::findToken($plainTextToken);

        // A null token means the value is not a real Sanctum token; anything that
        // is not an admin refresh token (e.g. an access token being replayed here)
        // is rejected too.
        if (!$token || $token->name !== self::REFRESH_TOKEN_NAME) {
            throw ValidationException::withMessages([
                'refreshToken' => __('auth.failed'),
            ]);
        }

        $admin = $token->tokenable;
        if (!$admin instanceof Admin) {
            throw ValidationException::withMessages([
                'refreshToken' => __('auth.failed'),
            ]);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'refreshToken' => __('auth.failed'),
            ]);
        }

        $expirationMinutes = config('sanctum.expiration') ?: 1440;

        // Rotate: burn the presented refresh token before minting its replacement.
        $token->delete();

        // Housekeeping so refreshes do not leave dead access tokens behind.
        $admin->tokens()
            ->where('name', self::ACCESS_TOKEN_NAME)
            ->where('expires_at', '<', now())
            ->delete();

        return $this->responseData([
            'tokens' => [
                'accessToken' => $admin->createToken(self::ACCESS_TOKEN_NAME, ['*'], now()->addMinutes($expirationMinutes))->plainTextToken,
                'refreshToken' => $admin->createToken(self::REFRESH_TOKEN_NAME, ['*'], now()->addDays(30))->plainTextToken,
                'expiresAt' => Carbon::now()->addMinutes($expirationMinutes)->toIso8601String(),
            ],
        ], 200);
    }
}
