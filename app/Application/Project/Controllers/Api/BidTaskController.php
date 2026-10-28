<?php

namespace App\Application\Project\Controllers\Api;

use App\Application\Project\Requests\Bids\AcceptTaskBidRequest;
use App\Application\Project\Requests\Bids\DirectAssignTaskBidRequest;
use App\Application\Project\Requests\Bids\RejectTaskBidRequest;
use App\Domain\Project\Actions\Bid\AcceptBidTaskAction;
use App\Domain\Project\Actions\Bid\DirectAssignEducatorAction;
use App\Domain\Project\Actions\Bid\RejectBidTaskAction;
use App\Domain\Project\Exceptions\BidException;
use App\Domain\Project\Resources\Bids\BidDetailResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use Domain\Project\Models\Bid;
use Domain\Project\Models\BidTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidTaskController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request, Bid $bid)
    {
        $bid = Bid::where('uuid', $bid->uuid)->with([
                'project.tasks',
                'bidTasks.task',
                'attachments',
                'links',
                'educator'
            ])
            ->firstOrFail();

        // if ($bid->educator_id !== auth()->id()) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        return $this->responseData(data:[
            'bid' => new BidDetailResource($bid),
        ]);
    }


    private function logBid(BidTask $bidTask, LogProjectActivityAction $log, string $key, array $extra = []): void
    {
        $task = $bidTask->task ?? $bidTask->bid?->bidTasks?->first()?->task;
        if ($task) {
            $log->forTask($task, $key, [
                'title' => $task->title,
                'educator' => $bidTask->bid?->educator?->name,
            ] + $extra);
        } elseif ($bidTask->bid?->project) {
            $log->forProject($bidTask->bid->project, $key, [
                'title' => $bidTask->bid->project->title,
                'educator' => $bidTask->bid?->educator?->name,
            ] + $extra);
        }
    }

    public function acceptTaskBid(AcceptTaskBidRequest $request, BidTask $bidTask, AcceptBidTaskAction $action, LogProjectActivityAction $logActivity)
    {
        try {
            $validatedData = $request->validated();
            $bidTask = $action->handle($bidTask, $validatedData);
            $this->logBid($bidTask->loadMissing(['task', 'bid.educator', 'bid.project']), $logActivity, LogProjectActivityAction::BID_APPROVED);

            return $this->responseData(data :['bid' => BidDetailResource::make($bidTask->bid->load('bidTasks.task','attachments'))], msg :'BidTask approve successfully');
        } catch(\Exception $e) {

            return $this->responseError(['error' => $e->getMessage()],msg:'Failed to approve bidTask');
        }
    }

    public function rejectTaskBid(RejectTaskBidRequest $request, BidTask $bidTask, RejectBidTaskAction $action, LogProjectActivityAction $logActivity)
    {
        try {
            $validatedData = $request->validated();
            $bidTask = $action->handle($bidTask, $validatedData);
            $this->logBid($bidTask->loadMissing(['task', 'bid.educator', 'bid.project']), $logActivity, LogProjectActivityAction::BID_REJECTED, [
                'reason' => $validatedData['rejection_reason'] ?? null,
            ]);

            return $this->responseData(data :['bid' => BidDetailResource::make($bidTask->bid->load('bidTasks.task','attachments'))], msg :'BidTask reject successfully');
        } catch(\Exception $e) {
            return $this->responseError(['error' => $e->getMessage()],$e->getCode(),'Failed to reject bidTask');
        }
    }

    public function directAssignEducator(DirectAssignTaskBidRequest $request, BidTask $bidTask, DirectAssignEducatorAction $action, LogProjectActivityAction $logActivity)
    {
        try {
            $validatedData = $request->validated();
            $action->handle($bidTask, $validatedData);
            $this->logBid($bidTask->loadMissing(['task', 'bid.educator', 'bid.project']), $logActivity, LogProjectActivityAction::BID_ASSIGNED);

            return $this->responseData(data :['bid' => BidDetailResource::make($bidTask->bid->load('bidTasks.task','attachments'))], msg :'BidTask assginmet educator successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError(['error' => $e->getMessage()],msg:'Failed to assginmet educator');
        }
    }

    public function cancelAssignEducator(DirectAssignTaskBidRequest $request, BidTask $bidTask, DirectAssignEducatorAction $action, LogProjectActivityAction $logActivity)
    {
        try {
            $validatedData = $request->validated();
            $action->handle($bidTask, $validatedData);
            $this->logBid($bidTask->loadMissing(['task', 'bid.educator', 'bid.project']), $logActivity, LogProjectActivityAction::ASSIGNMENT_CANCELLED);

            return $this->responseData(data :['bid' => BidDetailResource::make($bidTask->bid->load('bidTasks.task','attachments'))], msg :'BidTask assginmet educator successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError(['error' => $e->getMessage()],msg:'Failed to assginmet educator');
        }
    }
}
