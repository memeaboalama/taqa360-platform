<?php

namespace App\Application\Project\Controllers\Api;

use App\Application\Educator\Requests\Bids\FetchBidsRequest;
use App\Domain\Educator\Actions\Bids\FetchEducatorBidsAction;
use App\Domain\Educator\Actions\Bids\StoreBidAction;
use App\Domain\Educator\Actions\Bids\UpdateBidAction;
use App\Application\Educator\Requests\Bids\CreateBidRequest;
use App\Application\Educator\Requests\Bids\UpdateBidRequest;
use App\Application\Project\Requests\Bids\AcceptTaskBidRequest;
use App\Application\Project\Requests\Bids\DirectAssignTaskBidRequest;
use App\Application\Project\Requests\Bids\RejectTaskBidRequest;
use App\Domain\Project\Actions\Bid\DirectAssignEducatorAction;
use App\Domain\Project\Exceptions\BidException;
use App\Domain\Project\Resources\Bids\BidDetailResource;
use App\Domain\Project\Resources\Bids\BidResource;
use App\Domain\Workflow\Actions\Bid\AcceptBidTaskAction;
use App\Domain\Workflow\Actions\Bid\RejectBidTaskAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Educator\Models\Educator;
use Domain\Project\Models\Bid;
use Domain\Project\Models\BidTask;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BidController extends Controller
{
    use ApiResponseTrait;

    public function index(FetchBidsRequest $request, FetchEducatorBidsAction $fetchBidsAction): JsonResponse
    {
        $bids = $fetchBidsAction->handle($request->validated());

        return $this->responseData(data :['bids' => BidResource::collection($bids)], msg: 'Bids retrieved successfully');
    }

    public function show(Request $request, Bid $bid)
    {
        try {
            $bid = Bid::where('uuid', $bid->uuid)->with([
                    'project.tasks',
                    'bidTasks.task',
                    'attachments',
                    'links',
                    'educator.profile',
                    'educator.skills'
                ])
                ->firstOrFail();

            return $this->responseData(data:[
                'bid' => new BidDetailResource($bid),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

}
