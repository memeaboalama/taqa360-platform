<?php

namespace Tests\Feature;

use App\Domain\Driver\Repositories\HistoryDriverRepository;
use App\Domain\MarketPlace\Models\DriverHistory;
use Application\Admin\Livewire\Driver\History\All as HistoryAll;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

/**
 * Covers date/driver filter behaviour of the history screen (repository + Livewire).
 * All assertions are count-based (no hardcoded rows) so the suite is portable
 * to any environment. No data is written.
 */
class DriverHistoryFilterTest extends TestCase
{
    private function rowCount(TestableLivewire $component): int
    {
        $rows = $component->viewData('rows');

        if (is_object($rows) && method_exists($rows, 'total')) {
            return (int) $rows->total();
        }

        return is_countable($rows) ? count($rows) : -1;
    }

    // ---------------------------------------------------------------
    // Repository level
    // ---------------------------------------------------------------

    public function test_repository_null_date_returns_all_rows(): void
    {
        $repository = new HistoryDriverRepository(new DriverHistory());

        $count = $repository
            ->filter(['date_condition' => 'equal', 'date' => null])
            ->count();

        $this->assertSame(DriverHistory::count(), $count);
    }

    public function test_repository_between_without_to_date_returns_all_rows(): void
    {
        $repository = new HistoryDriverRepository(new DriverHistory());

        $count = $repository
            ->filter(['date_condition' => 'between', 'date' => '2026-10-18', 'to_date' => null])
            ->count();

        $this->assertSame(DriverHistory::count(), $count);
    }

    public function test_repository_unmatched_equal_date_returns_zero_rows(): void
    {
        $repository = new HistoryDriverRepository(new DriverHistory());

        $count = $repository
            ->filter(['date_condition' => 'equal', 'date' => '1900-01-01'])
            ->count();

        $this->assertSame(0, $count);
    }

    public function test_repository_unknown_driver_returns_zero_rows(): void
    {
        $repository = new HistoryDriverRepository(new DriverHistory());

        $count = $repository
            ->filter(['driver_id' => 999999999])
            ->count();

        $this->assertSame(0, $count);
    }

    // ---------------------------------------------------------------
    // Livewire screen level
    // ---------------------------------------------------------------

    public function test_screen_default_render_total_matches_table_count(): void
    {
        $component = Livewire::test(HistoryAll::class);

        $this->assertSame(DriverHistory::count(), $this->rowCount($component));
    }

    public function test_screen_applies_date_filter_on_first_request(): void
    {
        $component = Livewire::test(HistoryAll::class);

        $component->call('refreshDriverHistory', [
            'date_condition' => 'equal',
            'date'           => '1900-01-01',
        ]);

        // Regression: before setBuilder($this->builder()) the stored builder
        // was built before the filter was applied, so this stayed at the
        // full table count on the first request.
        $this->assertSame(0, $this->rowCount($component));
    }

    public function test_screen_null_date_shows_all_rows(): void
    {
        $component = Livewire::test(HistoryAll::class);

        $component->call('refreshDriverHistory', [
            'date_condition' => 'equal',
            'date'           => null,
            'to_date'        => null,
        ]);

        $this->assertSame(DriverHistory::count(), $this->rowCount($component));
    }

    public function test_screen_unknown_driver_shows_zero_rows(): void
    {
        $component = Livewire::test(HistoryAll::class);

        $component->call('refreshDriverHistory', ['driver_id' => 999999999]);

        $this->assertSame(0, $this->rowCount($component));
    }
}
