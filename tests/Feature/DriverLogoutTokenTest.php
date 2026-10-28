<?php

namespace Tests\Feature;

use App\Domain\MarketPlace\Models\DriverHistory;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Covers POST api/v1|v2/driver/auth/remove_token:
 * Exit logging, invalid/missing token behaviour, token ownership,
 * and SQL-injection safety.
 *
 * Every test runs inside a DB transaction that is ALWAYS rolled back:
 * no rows are committed to the local database.
 */
class DriverLogoutTokenTest extends TestCase
{
    private const NON_DRIVER_TYPE = 'App\\Models\\User';

    private function inTransaction(callable $fn): void
    {
        DB::beginTransaction();
        try {
            $fn();
        } finally {
            DB::rollBack();
        }
    }

    private function driverId(): int
    {
        return (int) (Driver::query()->orderBy('id')->value('id') ?? 36);
    }

    private function seedDeviceToken(string $token, string $type, int $ownerId): void
    {
        DB::table('device_tokens')->insert([
            'token'           => $token,
            'platform'        => 'android',
            'deviceable_type' => $type,
            'deviceable_id'   => $ownerId,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    /** Returns [id, plain] where plain is the "{id}|{secret}" client-facing token. */
    private function seedSanctumToken(string $type, int $ownerId): array
    {
        $secret = 'secret-' . bin2hex(random_bytes(8));
        $id = DB::table('personal_access_tokens')->insertGetId([
            'name'            => 'test',
            'token'           => hash('sha256', $secret),
            'abilities'       => '["*"]',
            'tokenable_type'  => $type,
            'tokenable_id'    => $ownerId,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return [$id, $id . '|' . $secret];
    }

    private function newExitRows(int $beforeMaxId): \Illuminate\Support\Collection
    {
        return DriverHistory::query()
            ->where('id', '>', $beforeMaxId)
            ->where('action', 'Exit')
            ->get();
    }

    // ---------------------------------------------------------------
    // v1: device_tokens lookup
    // ---------------------------------------------------------------

    public function test_v1_valid_device_token_records_exit_and_deletes_token(): void
    {
        $this->inTransaction(function () {
            $driverId = $this->driverId();
            $token = 'fcm-v1-' . bin2hex(random_bytes(10));
            $this->seedDeviceToken($token, Driver::class, $driverId);

            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v1/driver/auth/remove-token', ['device_token' => $token])
                ->assertStatus(200);

            $exits = $this->newExitRows($before);
            $this->assertCount(1, $exits);
            $this->assertSame($driverId, (int) $exits->first()->driver_id);
            $this->assertDatabaseMissing('device_tokens', ['token' => $token]);
        });
    }

    public function test_v1_sql_injection_payload_is_harmless(): void
    {
        $this->inTransaction(function () {
            // A non-driver token exists: the old raw-SQL `OR 1=1` would have
            // matched it and written a bogus Exit row for a non-driver id.
            $this->seedDeviceToken('legit-user-token', self::NON_DRIVER_TYPE, 999999);

            $beforeHistory = (int) DriverHistory::max('id');
            $beforeDevices = DB::table('device_tokens')->count();
            $beforeTokens  = DB::table('personal_access_tokens')->count();

            $this->postJson('/api/v1/driver/auth/remove-token', [
                'device_token' => "x' OR '1'='1",
            ])->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($beforeHistory)->count());
            $this->assertSame($beforeDevices, DB::table('device_tokens')->count());
            $this->assertSame($beforeTokens, DB::table('personal_access_tokens')->count());
            $this->assertDatabaseHas('device_tokens', ['token' => 'legit-user-token']);
        });
    }

    public function test_v1_missing_token_returns_success_without_changes(): void
    {
        $this->inTransaction(function () {
            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v1/driver/auth/remove-token', [])->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($before)->count());
        });
    }

    public function test_v1_token_owned_by_non_driver_is_ignored(): void
    {
        $this->inTransaction(function () {
            $token = 'fcm-user-' . bin2hex(random_bytes(8));
            $this->seedDeviceToken($token, self::NON_DRIVER_TYPE, 999999);

            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v1/driver/auth/remove-token', ['device_token' => $token])
                ->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($before)->count());
            $this->assertDatabaseHas('device_tokens', ['token' => $token]);
        });
    }

    // ---------------------------------------------------------------
    // v2: device_tokens lookup + Sanctum findToken fallback
    // ---------------------------------------------------------------

    public function test_v2_plain_text_auth_token_records_exit_and_revokes_token(): void
    {
        $this->inTransaction(function () {
            $driverId = $this->driverId();
            [$tokenId, $plain] = $this->seedSanctumToken(Driver::class, $driverId);

            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v2/driver/auth/remove-token', ['device_token' => $plain])
                ->assertStatus(200);

            $exits = $this->newExitRows($before);
            $this->assertCount(1, $exits);
            $this->assertSame($driverId, (int) $exits->first()->driver_id);
            $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
        });
    }

    public function test_v2_fcm_style_device_token_records_exit_via_device_tokens(): void
    {
        $this->inTransaction(function () {
            $driverId = $this->driverId();
            $token = 'fcm-v2-' . bin2hex(random_bytes(10));
            $this->seedDeviceToken($token, Driver::class, $driverId);

            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v2/driver/auth/remove-token', ['device_token' => $token])
                ->assertStatus(200);

            $exits = $this->newExitRows($before);
            $this->assertCount(1, $exits);
            $this->assertSame($driverId, (int) $exits->first()->driver_id);
            $this->assertDatabaseMissing('device_tokens', ['token' => $token]);
        });
    }

    public function test_v2_invalid_token_returns_success_without_changes(): void
    {
        $this->inTransaction(function () {
            $before = (int) DriverHistory::max('id');
            $beforeDevices = DB::table('device_tokens')->count();
            $beforeTokens  = DB::table('personal_access_tokens')->count();

            $this->postJson('/api/v2/driver/auth/remove-token', [
                'device_token' => 'definitely-not-a-token',
            ])->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($before)->count());
            $this->assertSame($beforeDevices, DB::table('device_tokens')->count());
            $this->assertSame($beforeTokens, DB::table('personal_access_tokens')->count());
        });
    }

    public function test_v2_sanctum_token_owned_by_non_driver_is_not_deleted(): void
    {
        $this->inTransaction(function () {
            [$tokenId, $plain] = $this->seedSanctumToken(self::NON_DRIVER_TYPE, 999999);

            $before = (int) DriverHistory::max('id');

            $this->postJson('/api/v2/driver/auth/remove-token', ['device_token' => $plain])
                ->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($before)->count());
            $this->assertDatabaseHas('personal_access_tokens', ['id' => $tokenId]);
        });
    }

    public function test_v2_sql_injection_payload_is_harmless(): void
    {
        $this->inTransaction(function () {
            $this->seedDeviceToken('legit-user-token-v2', self::NON_DRIVER_TYPE, 999999);

            $beforeHistory = (int) DriverHistory::max('id');
            $beforeDevices = DB::table('device_tokens')->count();
            $beforeTokens  = DB::table('personal_access_tokens')->count();

            $this->postJson('/api/v2/driver/auth/remove-token', [
                'device_token' => "x' OR '1'='1",
            ])->assertStatus(200);

            $this->assertSame(0, $this->newExitRows($beforeHistory)->count());
            $this->assertSame($beforeDevices, DB::table('device_tokens')->count());
            $this->assertSame($beforeTokens, DB::table('personal_access_tokens')->count());
        });
    }
}
