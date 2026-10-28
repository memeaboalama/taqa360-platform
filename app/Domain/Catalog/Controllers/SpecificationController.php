<?php

// namespace App\Domain\Catalog\Controllers\Admin\Catalog;
namespace App\Domain\Catalog\Controllers;

use App\Domain\Catalog\Models\Specification;
use App\Http\Controllers\Controller;
use App\Domain\Catalog\Resources\SpecificationResource;
use App\Domain\Catalog\Resources\SpecificationPropertyResource;
use App\Domain\Catalog\Data\Specification\SpecificationData;
use App\Domain\Catalog\Data\Specification\CreateSpecificationData;
use App\Domain\Catalog\Data\Specification\UpdateSpecificationRequest;
use App\Domain\Catalog\Actions\Specification\CreateSpecificationAction;
use App\Domain\Catalog\Actions\Specification\UpdateSpecificationAction;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class SpecificationController extends Controller
{
    use ApiResponseTrait;
    public function index(Request $request): JsonResponse
    {
        // Assuming item_id is passed as a query parameter
        $specifications = Specification::where('item_id', $request->query('item_id'))->with('properties')
        // ->get();
        ->paginate(18);
        // return response()->json(SpecificationResource::collection($specifications));
        return $this->responseSuccessData(SpecificationData::collect($specifications, PaginatedDataCollection::class)->include('properties'));
        // return $this->responseSuccessData(['data'=>SpecificationResource::collection($specifications)]);
    }

    public function store(CreateSpecificationData $data, CreateSpecificationAction $action): JsonResponse
    {
        $specification = $action->execute($data);
        // return response()->json(new SpecificationResource($specification->load('properties')), 201);
        return $this->responseSuccessData(new SpecificationResource($specification->load('properties')), 201);
    }

    public function show(Specification $specification): JsonResponse
    {
        return  $this->responseSuccessData(new  SpecificationResource($specification->load('properties')));
        // return response()->json(new SpecificationResource($specification->load('properties')));
    }

    public function update(Specification $specification, UpdateSpecificationRequest $data, UpdateSpecificationAction $action): JsonResponse
    {
        $specification = $action->execute($specification, $data);
        // return response()->json(new SpecificationResource($specification));
        return $this->responseSuccessData(new SpecificationResource($specification));
    }

    public function destroy(Specification $specification): JsonResponse
    {
        $specification->delete();
        // return response()->json(null, 204);
        return  $this->responseData([]);
    }
}