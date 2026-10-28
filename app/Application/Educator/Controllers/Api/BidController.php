<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Educator\Requests\Bids\FetchBidsRequest;
use App\Domain\Educator\Actions\Bids\FetchEducatorBidsAction;
use App\Domain\Educator\Actions\Bids\StoreBidAction;
use App\Domain\Educator\Actions\Bids\UpdateBidAction;
use App\Application\Educator\Requests\Bids\CreateBidRequest;
use App\Application\Educator\Requests\Bids\UpdateBidRequest;
use App\Domain\Project\Exceptions\BidException;
use App\Domain\Project\Resources\Bids\BidDetailResource;
use App\Domain\Project\Resources\Bids\BidResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Project\Models\Bid;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BidController extends Controller
{
    use ApiResponseTraitV2;

    public function index(FetchBidsRequest $request, FetchEducatorBidsAction $fetchBidsAction): JsonResponse
    {
        $bids = $fetchBidsAction->handle($request->validated());

        return $this->responseData(data :['bids' => BidResource::collection($bids)], msg: 'Bids retrieved successfully');
    }

    // public function stats(FetchBidsRequest $request, BidRepository $bidRepository): JsonResponse
    // {
    //     $stats = $bidRepository->getBidStats(Auth::id(), $request->validated());

    //     return $this->responseSuccess(['stats' => $stats], 'Bid statistics retrieved successfully');
    // }

    public function store(CreateBidRequest $request, StoreBidAction $storeBidAction): JsonResponse
    {
        try {
            $bid = $storeBidAction->handle($request->user(), $request->validated());

            return $this->responseData(data :['bid' => BidDetailResource::make($bid)], msg: 'Bid created successfully');
        } catch (\Exception $e) {
            return $this->responseError(['error' => $e->getMessage()],422,'Failed to create bid');
        }
    }

    public function update(UpdateBidRequest $request, Bid $bid, UpdateBidAction $updateBidAction): JsonResponse
    {
        Log::info('Update Bid Request', ['request' => $request->all(), 'bid_id' => $bid->id]);
        try {
            if ($bid->educator_id !== auth()->id()) {
                return $this->responseError([], 403, 'Unauthorized');
            }

            $updatedBid = $updateBidAction->handle($bid, $request->validated());
            return $this->responseData(data :['bid' => BidDetailResource::make($updatedBid)], msg :'Bid updated successfully');
        } catch (BidException $e) {
            return $this->responseError(['error' => $e->getMessage()],$e->getCode(),'Failed to update bid');
        } catch (\Exception $e) {
            return $this->responseError(['error' => $e->getMessage()],500,'Internal server error');
        }
    }

    public function show(Request $request, Bid $bid)
    {
        $bid = Bid::where('uuid', $bid->uuid)
            ->with([
                'project.tasks',
                'bidTasks.task',
                'attachments',
                'links',
                'educator'
            ])
            ->firstOrFail();

        if ($bid->educator_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->responseData(data:[
            'bid' => new BidDetailResource($bid),
        ]);
    }
}
