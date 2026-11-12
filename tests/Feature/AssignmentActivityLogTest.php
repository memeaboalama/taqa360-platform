<?php

namespace Tests\Feature;

use App\Domain\Project\Actions\Task\Assignmts\CancelAssignmentAction;
use Application\Project\Controllers\Api\TaskController;
use Domain\Educator\Models\Educator;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use Domain\Project\Models\Assignment;
use Domain\Project\Models\Task;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class AssignmentActivityLogTest extends TestCase
{
    public function test_cancellation_is_logged_only_after_the_action_succeeds(): void
    {
        $task = new Task();
        $task->title = 'مهمة اختبار';

        $educator = new Educator();
        $educator->name = 'متخصص اختبار';

        $assignment = Mockery::mock(Assignment::class)->makePartial();
        $assignment->setAttribute('id', 42);
        $assignment->setRelation('task', $task);
        $assignment->setRelation('educator', $educator);

        $actionCompleted = false;
        $cancelAction = Mockery::mock(CancelAssignmentAction::class);
        $cancelAction->shouldReceive('handle')
            ->once()
            ->with('تغيير الخطة', 42)
            ->andReturnUsing(function () use (&$actionCompleted, $assignment) {
                $actionCompleted = true;

                return $assignment;
            });

        $logActivity = Mockery::mock(LogProjectActivityAction::class);
        $logActivity->shouldReceive('forTask')
            ->once()
            ->with($task, LogProjectActivityAction::ASSIGNMENT_CANCELLED, [
                'educator' => 'متخصص اختبار',
                'title' => 'مهمة اختبار',
                'reason' => 'تغيير الخطة',
            ])
            ->andReturnUsing(function () use (&$actionCompleted) {
                if (! $actionCompleted) {
                    throw new \LogicException('Cancellation must succeed before its activity is logged.');
                }

                return null;
            });

        $assignment->setRelation('educator', $educator);
        $assignment->shouldReceive('load')->once()->with('educator')->andReturnSelf();

        $response = (new TaskController())->cancelAssignment(
            Request::create('/admin/tasks/assignments/42/cancel', 'POST', ['reason' => 'تغيير الخطة']),
            $assignment,
            $cancelAction,
            $logActivity
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($actionCompleted);
    }

    public function test_failed_cancellation_does_not_create_a_cancellation_activity(): void
    {
        $task = new Task();
        $task->title = 'مهمة اختبار';

        $assignment = Mockery::mock(Assignment::class)->makePartial();
        $assignment->setAttribute('id', 42);
        $assignment->setRelation('task', $task);

        $cancelAction = Mockery::mock(CancelAssignmentAction::class);
        $cancelAction->shouldReceive('handle')
            ->once()
            ->andThrow(new \RuntimeException('لا يمكن إلغاء التعيين.'));

        $logActivity = Mockery::mock(LogProjectActivityAction::class);
        $logActivity->shouldNotReceive('forTask');

        $response = (new TaskController())->cancelAssignment(
            Request::create('/admin/tasks/assignments/42/cancel', 'POST'),
            $assignment,
            $cancelAction,
            $logActivity
        );

        $this->assertSame(400, $response->getStatusCode());
    }
}
