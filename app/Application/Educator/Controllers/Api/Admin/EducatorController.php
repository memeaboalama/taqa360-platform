<?php

namespace App\Application\Educator\Controllers\Api\Admin;

use App\Application\Educator\Enums\EducatorStatus;
use App\Application\Educator\Requests\FetchEducatorRequest;
use App\Domain\Educator\Actions\FetchEducatorAction;
use Domain\Educator\Models\Educator;
use App\Domain\Educator\Resource\EducatorResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    use ApiResponseTraitV2;
    public function __construct()
    {
        // $this->authorizeResource(Educator::class, 'educator');
    }

    public function fetch(FetchEducatorRequest $request, FetchEducatorAction $fetchEducatorAction)
    {
        $educators = $fetchEducatorAction->handle($request->validated());
        return $this->responseData(data: [
            'educators' => EducatorResource::collection($educators),
        ]);
    }

    public function updateStatus(Request $request, string $educatorId)
    {
        $statusInput = strtolower($request->input('status'));

        $request->merge([
            'status' => $statusInput,
        ]);

        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', EducatorStatus::values())],
        ]);

        $educator = Educator::where('uuid', $educatorId)->firstOrFail();

        $newStatus = EducatorStatus::from($statusInput);

        $educator->update(['status' => $newStatus]);

        return $this->responseData(data: [
            'educator' => new EducatorResource($educator->fresh()),
        ], msg: 'Educator status updated successfully.');
    }
}
