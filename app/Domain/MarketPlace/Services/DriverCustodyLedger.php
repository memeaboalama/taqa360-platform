<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;
use Domain\Driver\Models\Driver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DriverCustodyLedger
{
    public function driverSummaries(?string $search = null, int $perPage = 25): LengthAwarePaginator
    {
        $balances = DB::query()
            ->fromSub($this->baseMovements(), 'driver_custody_movements')
            ->select('driver_id')
            ->selectRaw('SUM(amount) as custody_balance')
            ->groupBy('driver_id')
            ->get()
            ->keyBy('driver_id');

        $pendingSettlements = DB::table('order_settlements')
            ->whereIn('status', ['pending_review', 'reviewed'])
            ->select('driver_id')
            ->selectRaw('SUM(amount) as pending_settlement_amount')
            ->groupBy('driver_id')
            ->get()
            ->keyBy('driver_id');

        $drivers = Driver::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $drivers->getCollection()->transform(function (Driver $driver) use ($balances, $pendingSettlements) {
            $driver->setAttribute(
                'custody_balance',
                round((float) ($balances->get($driver->id)?->custody_balance ?? 0), 2)
            );
            $driver->setAttribute(
                'pending_settlement_amount',
                round((float) ($pendingSettlements->get($driver->id)?->pending_settlement_amount ?? 0), 2)
            );

            return $driver;
        });

        return $drivers;
    }

    public function statement(int $driverId, array $filters = []): array
    {
        $validated = Validator::make($filters, [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'type' => ['nullable', 'in:collection,settlement,correction,refund'],
            'order' => ['nullable', 'string', 'max:100'],
            'settlement' => ['nullable', 'string', 'max:100'],
        ])->validate();

        $from = !empty($validated['from'])
            ? Carbon::parse($validated['from'])->startOfDay()
            : null;
        $to = !empty($validated['to'])
            ? Carbon::parse($validated['to'])->endOfDay()
            : now()->endOfDay();

        if ($from && $from->greaterThan($to)) {
            throw ValidationException::withMessages([
                'from' => __('admin::home.from_date_must_precede_to_date'),
            ]);
        }

        $allMovements = DB::query()->fromSub($this->baseMovements($driverId), 'ledger');
        $openingBalance = $from
            ? $this->balanceFromMovements((clone $allMovements)->where('occurred_at', '<', $from))
            : 0.0;

        $periodMovements = clone $allMovements;
        if ($from) {
            $periodMovements->where('occurred_at', '>=', $from);
        }
        $periodMovements->where('occurred_at', '<=', $to);
        $closingBalance = $openingBalance + $this->balanceFromMovements(clone $periodMovements);

        $movements = clone $periodMovements;
        if (!empty($validated['type'])) {
            $movements->where('movement_type', $validated['type']);
        }
        if (!empty($validated['order'])) {
            $search = trim((string) $validated['order']);
            $movements->where(function ($query) use ($search) {
                $query->where('order_code', 'like', '%' . $search . '%')
                    ->orWhere('order_id', $search);
            });
        }
        if (!empty($validated['settlement'])) {
            $settlementId = preg_replace('/^STL-/i', '', trim((string) $validated['settlement']));
            if (ctype_digit($settlementId)) {
                $movements->where('settlement_id', (int) $settlementId);
            } else {
                $movements->whereRaw('1 = 0');
            }
        }

        $rows = $movements
            ->orderBy('occurred_at')
            ->orderBy('record_id')
            ->paginate(50)
            ->withQueryString();

        return [
            'movements' => $rows,
            'opening_balance' => round($openingBalance, 2),
            'closing_balance' => round($closingBalance, 2),
            'from' => $from?->toDateString(),
            'to' => $to->toDateString(),
        ];
    }

    public function balanceForOrder(int $orderId, int $driverId): float
    {
        $movements = DB::query()
            ->fromSub($this->baseMovements($driverId), 'ledger')
            ->where('order_id', $orderId);

        return $this->balanceFromMovements($movements);
    }

    public static function calculateBalance(
        float $collections,
        float $approvedSettlements,
        float $corrections,
        float $driverFundedRefunds
    ): float {
        return round($collections - $approvedSettlements + $corrections - $driverFundedRefunds, 2);
    }

    private function baseMovements(?int $driverId = null): Builder
    {
        $collection = DB::table('payments as p')
            ->leftJoin('order_settlements as s', function ($join) {
                $join->on('s.order_id', '=', 'p.payable_id')
                    ->where(function ($query) {
                        $query->on('s.id', '=', 'p.settlement_id')
                            ->orWhere(function ($query) {
                                $query->whereNull('p.settlement_id')
                                    ->whereRaw('s.id = (SELECT MAX(s_latest.id) FROM order_settlements AS s_latest WHERE s_latest.order_id = s.order_id)');
                            });
                    });
            })
            ->join('merchant_orders as o', 'o.id', '=', 's.order_id')
            ->whereIn('p.payable_type', [
                (new MerchantOrder())->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->whereIn('p.status', ['paid', 'success'])
            ->where('p.source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
            ->whereRaw('COALESCE(s.driver_id, o.driver_id) IS NOT NULL')
            ->whereRaw('COALESCE(NULLIF(p.captured, 0), p.amount) > 0')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('payments as duplicate')
                    ->whereColumn('duplicate.payable_id', 'p.payable_id')
                    ->whereColumn('duplicate.payable_type', 'p.payable_type')
                    ->whereColumn('duplicate.payment_id', 'p.payment_id')
                    ->whereColumn('duplicate.id', '>', 'p.id')
                    ->whereIn('duplicate.status', ['paid', 'success'])
                    ->where('duplicate.source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE);
            })
            ->when($driverId, fn ($query) => $query->where('s.driver_id', $driverId))
            ->selectRaw("
                COALESCE(s.driver_id, o.driver_id) as driver_id,
                p.id as record_id,
                p.payment_id as movement_number,
                p.created_at as occurred_at,
                'collection' as movement_type,
                CAST(COALESCE(NULLIF(p.captured, 0), p.amount) AS DECIMAL(12,2)) / 100 as amount,
                o.id as order_id,
                o.code as order_code,
                s.id as settlement_id,
                s.status as settlement_status,
                COALESCE(p.description, p.source_message) as details,
                'collection' as source_movement_type,
                p.id as source_movement_id
            ");

        $settlements = DB::table('order_settlements as s')
            ->join('merchant_orders as o', 'o.id', '=', 's.order_id')
            ->where('s.status', 'approved')
            ->whereNotNull('s.driver_id')
            ->when($driverId, fn ($query) => $query->where('s.driver_id', $driverId))
            ->selectRaw("
                s.driver_id,
                s.id as record_id,
                CONCAT('STL-', s.id) as movement_number,
                COALESCE(s.approved_at, s.updated_at, s.created_at) as occurred_at,
                'settlement' as movement_type,
                -s.amount as amount,
                o.id as order_id,
                o.code as order_code,
                s.id as settlement_id,
                s.status as settlement_status,
                CONCAT('توريد معتمد: ', s.recipient_name) as details,
                'settlement' as source_movement_type,
                s.id as source_movement_id
            ");

        $corrections = DB::table('order_settlement_corrections as c')
            ->join('order_settlements as s', 's.id', '=', 'c.settlement_id')
            ->join('merchant_orders as o', 'o.id', '=', 's.order_id')
            ->leftJoin('admins as a', 'a.id', '=', 'c.corrected_by')
            ->whereNotNull('s.driver_id')
            ->when($driverId, fn ($query) => $query->where('s.driver_id', $driverId))
            ->selectRaw("
                s.driver_id,
                c.id as record_id,
                CONCAT('COR-', c.id) as movement_number,
                c.created_at as occurred_at,
                'correction' as movement_type,
                c.amount as amount,
                o.id as order_id,
                o.code as order_code,
                s.id as settlement_id,
                s.status as settlement_status,
                CONCAT(c.reason, ' — ', COALESCE(a.name, '')) as details,
                'settlement' as source_movement_type,
                s.id as source_movement_id
            ");

        $movementCorrections = DB::table('payment_correction_requests as c')
            ->join('merchant_orders as o', 'o.id', '=', 'c.order_id')
            ->leftJoin('order_settlements as s', 's.id', '=', 'c.settlement_id')
            ->leftJoin('admins as a', 'a.id', '=', 'c.approved_by')
            ->where('c.status', 'approved')
            ->whereIn('c.movement_type', ['collection', 'settlement'])
            ->whereRaw('COALESCE(s.driver_id, o.driver_id) IS NOT NULL')
            ->when($driverId, fn ($query) => $query->whereRaw('COALESCE(s.driver_id, o.driver_id) = ?', [$driverId]))
            ->selectRaw("
                COALESCE(s.driver_id, o.driver_id) as driver_id,
                c.id as record_id,
                CONCAT('COR-', c.id) as movement_number,
                c.approved_at as occurred_at,
                'correction' as movement_type,
                CAST(CASE WHEN c.movement_type = 'settlement'
                    THEN -c.adjustment_amount
                    ELSE c.adjustment_amount
                END AS DECIMAL(12,2)) as amount,
                o.id as order_id,
                o.code as order_code,
                c.settlement_id as settlement_id,
                s.status as settlement_status,
                CONCAT(c.reason, ' — ', COALESCE(a.name, '')) as details,
                c.movement_type as source_movement_type,
                c.movement_id as source_movement_id
            ");

        $refunds = DB::table('refund_payments as r')
            ->join('merchant_orders as o', 'o.id', '=', 'r.order_id')
            ->where('r.funded_by', 'driver')
            ->whereNotNull('r.driver_id')
            ->when($driverId, fn ($query) => $query->where('r.driver_id', $driverId))
            ->selectRaw("
                r.driver_id,
                r.id as record_id,
                r.refund_payment_id as movement_number,
                r.created_at as occurred_at,
                'refund' as movement_type,
                -r.amount as amount,
                o.id as order_id,
                o.code as order_code,
                (SELECT s.id FROM order_settlements s WHERE s.order_id = o.id LIMIT 1) as settlement_id,
                (SELECT s.status FROM order_settlements s WHERE s.order_id = o.id LIMIT 1) as settlement_status,
                r.note as details,
                'refund' as source_movement_type,
                r.id as source_movement_id
            ");

        return $collection
            ->unionAll($settlements)
            ->unionAll($corrections)
            ->unionAll($movementCorrections)
            ->unionAll($refunds);
    }

    private function balanceFromMovements(Builder $movements): float
    {
        $amounts = (clone $movements)
            ->select('movement_type')
            ->selectRaw('SUM(amount) as movement_amount')
            ->groupBy('movement_type')
            ->get()
            ->keyBy('movement_type');

        return self::calculateBalance(
            (float) ($amounts->get('collection')?->movement_amount ?? 0),
            abs((float) ($amounts->get('settlement')?->movement_amount ?? 0)),
            (float) ($amounts->get('correction')?->movement_amount ?? 0),
            abs((float) ($amounts->get('refund')?->movement_amount ?? 0))
        );
    }
}
