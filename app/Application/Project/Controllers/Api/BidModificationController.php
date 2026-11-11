<?php

namespace App\Application\Project\Controllers\Api;

use App\Application\Project\Enums\BidStatus;
use App\Http\Controllers\Controller;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use Domain\Project\Models\Bid;
use Domain\Project\Models\BidModificationRequest;
use Domain\Project\Models\BidTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BidModificationController extends Controller
{
    public function store(Request $request, Bid $bid, LogProjectActivityAction $activity): JsonResponse
    {
        if ((string) $bid->educator_id !== (string) auth()->id()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'proposal' => ['required', 'string', 'max:10000'],
            'delivery_days' => ['required', 'integer', 'min:1'],
            'bid_tasks' => ['required', 'array', 'min:1'],
            'bid_tasks.*.task_id' => ['required', 'string', 'distinct', 'exists:tasks,uuid'],
            'bid_tasks.*.amount' => ['required', 'numeric', 'min:0'],
        ]);

        $modification = DB::transaction(function () use ($bid, $validated) {
            $lockedBid = Bid::query()->whereKey($bid->id)->lockForUpdate()->firstOrFail();
            $tasks = $lockedBid->bidTasks()->with('task')->lockForUpdate()->get();
            $this->assertModificationWindow($tasks);

            $existingTaskIds = $tasks->map(fn (BidTask $task) => (string) $task->task->uuid)->sort()->values()->all();
            $requestedTasks = collect($validated['bid_tasks'])
                ->map(fn (array $task) => [
                    'task_id' => (string) $task['task_id'],
                    'amount' => round((float) $task['amount'], 2),
                ])
                ->sortBy('task_id')
                ->values();

            if ($existingTaskIds !== $requestedTasks->pluck('task_id')->sort()->values()->all()) {
                throw ValidationException::withMessages([
                    'bid_tasks' => ['تعديل الاتفاق لا يسمح بإضافة مهام العرض أو حذفها.'],
                ]);
            }

            if ($lockedBid->modificationRequests()->where('status', 'PENDING')->exists()) {
                throw ValidationException::withMessages([
                    'modification' => ['يوجد طلب تعديل قيد المراجعة بالفعل.'],
                ]);
            }

            $previous = $this->snapshot($lockedBid, $tasks);
            $requested = [
                'proposal' => $validated['proposal'],
                'deliveryDays' => (int) $validated['delivery_days'],
                'tasks' => $requestedTasks->all(),
            ];

            if ($previous === $requested) {
                throw ValidationException::withMessages([
                    'modification' => ['لم يتم إدخال أي تغيير على العرض أو شروطه.'],
                ]);
            }

            return $lockedBid->modificationRequests()->create([
                'educator_id' => $lockedBid->educator_id,
                'status' => 'PENDING',
                'previous_values' => $previous,
                'requested_values' => $requested,
            ]);
        });

        $activity->forProject($bid->project, LogProjectActivityAction::BID_MODIFICATION_REQUESTED, [
            'educator' => $bid->educator?->name,
            'request_id' => $modification->uuid,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال طلب تعديل العرض إلى الإدارة للمراجعة.',
            'data' => ['request' => $this->format($modification)],
        ], 201);
    }

    public function decide(Request $request, BidModificationRequest $modification, LogProjectActivityAction $activity): JsonResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:APPROVED,REJECTED'],
            'decision_note' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['decision'] === 'REJECTED' && trim((string) ($validated['decision_note'] ?? '')) === '') {
            throw ValidationException::withMessages([
                'decision_note' => ['سبب رفض طلب التعديل مطلوب.'],
            ]);
        }

        [$modification, $bid] = DB::transaction(function () use ($modification, $validated) {
            $lockedModification = BidModificationRequest::query()
                ->whereKey($modification->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($lockedModification->status !== 'PENDING') {
                abort(409, 'تم اتخاذ قرار بشأن طلب التعديل مسبقًا.');
            }

            $bid = Bid::query()->whereKey($lockedModification->bid_id)->lockForUpdate()->firstOrFail();
            if ($validated['decision'] === 'APPROVED') {
                $tasks = $bid->bidTasks()->with('task')->lockForUpdate()->get();
                $this->assertModificationWindow($tasks);

                $current = $this->snapshot($bid, $tasks);
                if (! $this->snapshotsMatch($current, $lockedModification->previous_values)) {
                    throw ValidationException::withMessages([
                        'modification' => ['تغير العرض منذ تقديم الطلب. أعد فتح العرض وأرسل طلبًا جديدًا.'],
                    ]);
                }

                $requested = $lockedModification->requested_values;
                $bid->update([
                    'proposal' => $requested['proposal'],
                    'delivery_days' => $requested['deliveryDays'],
                ]);
                $amounts = collect($requested['tasks'])->keyBy('task_id');
                foreach ($tasks as $task) {
                    $task->update([
                        'amount' => $amounts[(string) $task->task->uuid]['amount'],
                    ]);
                }
            }

            $lockedModification->update([
                'status' => $validated['decision'],
                'decision_note' => isset($validated['decision_note']) ? trim($validated['decision_note']) : null,
                'decided_by' => auth()->id(),
                'decided_at' => now(),
            ]);

            return [$lockedModification->fresh(), $bid];
        });

        if ($modification->status !== 'PENDING') {
            $activity->forProject(
                $bid->project,
                $modification->status === 'APPROVED'
                    ? LogProjectActivityAction::BID_MODIFICATION_APPROVED
                    : LogProjectActivityAction::BID_MODIFICATION_REJECTED,
                [
                    'educator' => $bid->educator?->name,
                    'request_id' => $modification->uuid,
                    'previous_values' => $modification->previous_values,
                    'requested_values' => $modification->requested_values,
                    'reason' => $modification->decision_note,
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث قرار طلب تعديل العرض.',
            'data' => ['request' => $this->format($modification)],
        ]);
    }

    private function assertModificationWindow($tasks): void
    {
        $statuses = $tasks->map(fn (BidTask $task) => $task->status instanceof \UnitEnum
            ? $task->status->value
            : (string) $task->status);

        if ($statuses->isEmpty()
            || ! $statuses->contains(BidStatus::APPROVED->value)
            || $statuses->contains(BidStatus::ASSIGNED->value)
            || $statuses->contains(BidStatus::REJECTED->value)) {
            abort(409, 'تعديل الاتفاق متاح بعد القبول وقبل تعيين المهمة للتنفيذ فقط.');
        }
    }

    private function snapshot(Bid $bid, $tasks): array
    {
        return [
            'proposal' => $bid->proposal,
            'deliveryDays' => (int) $bid->delivery_days,
            'tasks' => $tasks->map(fn (BidTask $task) => [
                'task_id' => (string) $task->task->uuid,
                'amount' => round((float) $task->amount, 2),
            ])->sortBy('task_id')->values()->all(),
        ];
    }

    private function snapshotsMatch(array $first, array $second): bool
    {
        $normalize = static function (array $snapshot): array {
            $tasks = array_map(static fn (array $task) => [
                'task_id' => (string) ($task['task_id'] ?? ''),
                'amount' => number_format((float) ($task['amount'] ?? 0), 2, '.', ''),
            ], $snapshot['tasks'] ?? []);

            usort($tasks, static fn (array $a, array $b) => strcmp($a['task_id'], $b['task_id']));

            return [
                'proposal' => (string) ($snapshot['proposal'] ?? ''),
                'deliveryDays' => (int) ($snapshot['deliveryDays'] ?? 0),
                'tasks' => $tasks,
            ];
        };

        return $normalize($first) === $normalize($second);
    }

    private function format(BidModificationRequest $modification): array
    {
        return [
            'id' => $modification->uuid,
            'status' => $modification->status,
            'previousValues' => $modification->previous_values,
            'requestedValues' => $modification->requested_values,
            'decisionNote' => $modification->decision_note,
            'createdAt' => $modification->created_at?->toISOString(),
            'decidedAt' => $modification->decided_at?->toISOString(),
        ];
    }
}
