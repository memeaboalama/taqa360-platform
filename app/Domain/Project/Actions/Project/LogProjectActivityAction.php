<?php

namespace Domain\Project\Actions\Project;

use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectActivityLog;
use Domain\Project\Models\Task;
use Domain\Project\Models\TaskActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Writes the audit trail shown in the "سجل الأحداث" tab.
 *
 * Everything goes through here so the shape of an entry is consistent: a
 * message_key the UI translates, a payload of interpolated values, and who did
 * it. Logging must never break the operation being logged, so any failure is
 * swallowed.
 */
class LogProjectActivityAction
{
    /** Grep tag: every diagnostic line below starts with this. */
    public const TAG = '[ACT-LOG]';

    /** Cached once per request: are the log tables actually there? */
    private static array $tableState = [];

    /**
     * Checks ONE table at a time. An earlier version required both tables, which
     * meant a missing task_activity_logs silently disabled project logging too.
     */
    private function tableReady(string $table): bool
    {
        if (array_key_exists($table, self::$tableState)) {
            return self::$tableState[$table];
        }

        try {
            $exists = Schema::hasTable($table);
            self::$tableState[$table] = $exists;

            $this->trace('table-check', ['table' => $table, 'exists' => $exists]);

            if (! $exists) {
                Log::error(self::TAG . ' TABLE MISSING - run: php artisan migrate --force', [
                    'table' => $table,
                    'db' => config('database.connections.' . config('database.default') . '.database'),
                ]);
            }
        } catch (\Throwable $e) {
            $this->trace('table-check:FAILED', ['table' => $table], $e);
            self::$tableState[$table] = false;
        }

        return self::$tableState[$table];
    }

    public const PROJECT_CREATED = 'project.created';
    public const PROJECT_UPDATED = 'project.updated';
    public const PROJECT_STATUS_CHANGED = 'project.status_changed';
    public const PROJECT_PUBLISHED = 'project.published';
    public const PROJECT_UNPUBLISHED = 'project.unpublished';
    public const PROJECT_ATTACHMENT_ADDED = 'project.attachment_added';

    public const TASK_CREATED = 'task.created';
    public const TASK_UPDATED = 'task.updated';
    public const TASK_STATUS_CHANGED = 'task.status_changed';
    public const TASK_DELETED = 'task.deleted';
    public const TASK_BIDDING_OPENED = 'task.bidding_opened';
    public const TASK_BIDDING_CLOSED = 'task.bidding_closed';
    public const TASK_ATTACHMENT_ADDED = 'task.attachment_added';
    public const TASK_NOTE_ADDED = 'task.note_added';

    public const EDUCATOR_ASSIGNED = 'task.educator_assigned';
    public const ASSIGNMENT_ACCEPTED = 'task.assignment_accepted';
    public const ASSIGNMENT_REJECTED = 'task.assignment_rejected';
    public const ASSIGNMENT_CANCELLED = 'task.assignment_cancelled';
    public const ASSIGNMENT_STATUS_CHANGED = 'task.assignment_status_changed';
    /** @deprecated kept for backward compatibility, use TASK_ATTACHMENT_ADDED */
    public const ATTACHMENT_ADDED = 'task.attachment_added';

    public const BID_APPROVED = 'bid.approved';
    public const BID_REJECTED = 'bid.rejected';
    public const BID_ASSIGNED = 'bid.assigned';

    /** Best-effort actor label: whoever the current guard resolves to. */
    private function actorName(): ?string
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return null;
            }
            return $user->name ?? $user->email ?? class_basename($user);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Writes one diagnostic line to laravel.log. Never throws: tracing must not
     * be able to break the operation being traced.
     */
    public function trace(string $step, array $context = [], ?\Throwable $e = null): void
    {
        try {
            $line = [
                'db' => config('database.connections.' . config('database.default') . '.database'),
                'actor' => $this->actorName(),
            ] + $context;

            if ($e !== null) {
                $line['error'] = get_class($e) . ': ' . $e->getMessage();
                $line['at'] = $e->getFile() . ':' . $e->getLine();
            }

            Log::info(self::TAG . ' ' . $step, $line);

            if ($e !== null) {
                Log::error(self::TAG . ' exception', [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'origin' => $e->getFile() . ':' . $e->getLine(),
                    'top' => collect($e->getTrace())->take(6)->map(fn($f) =>
                        ($f['file'] ?? '?') . ':' . ($f['line'] ?? '?')
                    )->implode(' <- '),
                ]);
            }
        } catch (\Throwable $ignored) {
            // Tracing must never break anything.
        }
    }

    /**
     * True when the log tables exist. A missing table is the single most common
     * reason for a silently empty activity tab, so it gets its own loud line.
     */
    public function forProject(
        Project $project,
        string $messageKey,
        array $payload = [],
        ?string $actor = null
    ): ?ProjectActivityLog {
        $ctx = ['step' => 'forProject', 'key' => $messageKey, 'project_id' => $project->id ?? null];
        $this->trace('enter', $ctx);

        if (! $this->tableReady('project_activity_logs')) {
            $this->trace('skipped:no-tables', $ctx);
            return null;
        }

        try {
            $log = ProjectActivityLog::create([
                'project_id' => $project->id,
                'message_key' => $messageKey,
                'message_payload' => $payload,
                'actor' => $actor ?? $this->actorName(),
                'timestamp' => now(),
            ]);

            $this->trace('written', $ctx + ['log_id' => $log->id]);

            return $log;
        } catch (\Throwable $e) {
            $this->trace('WRITE FAILED', $ctx, $e);
            report($e);
            return null;
        }
    }

    /**
     * Logs a task event on the task and, when the caller asks for it, mirrors it
     * onto the project so the project-level tab shows the full picture.
     */
    public function forTask(
        Task $task,
        string $messageKey,
        array $payload = [],
        bool $alsoOnProject = true
    ): ?TaskActivityLog {
        $ctx = ['step' => 'forTask', 'key' => $messageKey, 'task_id' => $task->id ?? null];
        $this->trace('enter', $ctx);

        if (! $this->tableReady('task_activity_logs')) {
            $this->trace('skipped:no-tables', $ctx);

            // The task table is gone but the project table may be fine - still
            // record the event on the project so the tab is not left empty.
            if ($alsoOnProject && $task->project) {
                $this->forProject($task->project, $messageKey, $payload, $this->actorName());
            }

            return null;
        }

        try {
            $log = TaskActivityLog::create([
                'task_id' => $task->id,
                'message_key' => $messageKey,
                'message_payload' => $payload,
                'actor_name' => $this->actorName(),
                'timestamp' => now(),
            ]);

            $this->trace('written', $ctx + ['log_id' => $log->id]);

            if ($alsoOnProject && $task->project) {
                // ProjectActivityLog uses `actor`, TaskActivityLog uses `actor_name`
                $this->forProject($task->project, $messageKey, $payload, $this->actorName());
            } else {
                $this->trace('mirror skipped (no project relation)', $ctx);
            }

            return $log;
        } catch (\Throwable $e) {
            $this->trace('WRITE FAILED', $ctx, $e);
            report($e);
            return null;
        }
    }
}
