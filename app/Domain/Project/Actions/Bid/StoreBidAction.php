<?php

namespace App\Domain\Project\Actions\Bid;

use Domain\Project\Models\Bid;
use Domain\Project\Models\Workflow;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use App\Domain\Project\Repositories\BidRepository;

class StoreBidAction
{
    public function __construct(protected BidRepository $bidRepository){}
    public function handle(Educator $educator, Workflow $workflow, array $data): Bid
    {
        Validator::make($data, [
            'amount' => 'required|numeric|min:1',
            'cover_letter' => 'required|string|min:50',
            'delivery_days' => 'required|integer|min:1',
        ])->validate();

        if ($workflow->type !== WorkflowType::PUBLIC_TENDER) {
            throw new \Exception("Bids can only be submitted for public tenders.");
        }
        if ($workflow->status !== WorkflowStatus::OPEN_FOR_BIDS) {
            throw new \Exception("This tender is no longer open for bids.");
        }

        $bid = $workflow->bids()->create([
            'educator_id' => $educator->id,
            'amount' => $data['amount'],
            'cover_letter' => $data['cover_letter'],
            'delivery_days' => $data['delivery_days'],
        ]);

        // event(new BidSubmitted($bid));

        return $bid;
    }
}
