<?php

namespace App\Domain\Educator\Actions\Bids;

use App\Domain\Project\Repositories\BidRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class FetchEducatorBidsAction
{
    public function __construct(
        protected BidRepository $bidRepository
    ) {}

    public function handle(array $filters = []): LengthAwarePaginator|Collection
    {
        return $this->bidRepository->getEducatorBids(Auth::id(), $filters);
    }
}
