<?php

namespace Tests\Unit;

use App\Application\Project\Controllers\Api\BidModificationController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class BidModificationSnapshotTest extends TestCase
{
    public function test_it_matches_snapshots_when_json_changes_integral_amounts_to_integers(): void
    {
        $controller = (new ReflectionClass(BidModificationController::class))->newInstanceWithoutConstructor();
        $method = new \ReflectionMethod(BidModificationController::class, 'snapshotsMatch');
        $method->setAccessible(true);

        $snapshot = [
            'proposal' => 'Updated proposal',
            'deliveryDays' => 10,
            'tasks' => [
                ['task_id' => 'task-1', 'amount' => 100.0],
                ['task_id' => 'task-2', 'amount' => 25.5],
            ],
        ];
        $storedSnapshot = [
            'tasks' => [
                ['amount' => 25.5, 'task_id' => 'task-2'],
                ['amount' => 100, 'task_id' => 'task-1'],
            ],
            'deliveryDays' => 10,
            'proposal' => 'Updated proposal',
        ];

        $this->assertTrue($method->invoke($controller, $snapshot, $storedSnapshot));
        $storedSnapshot['tasks'][0]['amount'] = 26;
        $this->assertFalse($method->invoke($controller, $snapshot, $storedSnapshot));
    }
}
