<?php

// namespace App\Domain\Catalog\Controllers\Admin\Catalog;
namespace App\Domain\Catalog\Controllers;

use App\Domain\Catalog\Actions\Attachment\AttachExistingAttachmentsAction;
use App\Domain\Catalog\Actions\Attachment\BulkDeleteAttachmentsAction;
use App\Domain\Catalog\Models\Attachment;
use App\Http\Controllers\Controller;
use App\Domain\Catalog\Resources\AttachmentResource;
use App\Domain\Catalog\Resources\AttachmentPropertyResource;
use App\Domain\Catalog\Data\Attachment\AttachmentData;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Domain\Catalog\Data\Attachment\UpdateAttachmentRequest;
use App\Domain\Catalog\Actions\Attachment\CreateAttachmentAction;
use App\Domain\Catalog\Actions\Attachment\DeleteAttachmentAction;
use App\Domain\Catalog\Actions\Attachment\DetachExistingAttachmentsAction;
use App\Domain\Catalog\Actions\Attachment\ReorderAttachmentsAction;
use App\Domain\Catalog\Actions\Attachment\SetPrimaryAttachmentAction;
use App\Domain\Catalog\Actions\Attachment\UpdateAttachmentAction;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Domain\MarketPlace\Models\Item;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\PaginatedDataCollection;

class AttachmentController extends Controller
{
    use ApiResponseTrait;


    /**
     * List all attachments for a specific item.
     */
    public function index(Item $item): JsonResponse
    {
        // $item = Item::findOrFail($data->item_id);
        // return response()->json(AttachmentResource::collection($item->attachments));
        return $this->responseSuccessData(['data'=>AttachmentResource::collection($item->attachments)]);
        // return $this->responseSuccessData(['data'=>$item->title]);
    }

    /**
     * Store a new attachment for a specific item.
     */
    public function store(Request $request, Item $item, CreateAttachmentAction $action): JsonResponse
    {
        // Manually create the DTO to add the polymorphic relationship details
        $attachmentData = CreateAttachmentData::from(array_merge($request->all(), [
            'attachable_id' => $item->id,
            'attachable_type' => get_class($item),
            'meta'=> [
                'merchant_id'=> $item->merchant_id,
            ]
        ]));
        
        $attachment = $action->execute($attachmentData);
        
        // return response()->json(new AttachmentResource($attachment), 201);
        return $this->responseSuccessData(new AttachmentResource($attachment), 201);
    }

    /**
     * Update an attachment's details (e.g., description).
     */
    public function update(Request $request, Attachment $attachment, UpdateAttachmentAction $action): JsonResponse
    {
        $updateData = UpdateAttachmentRequest::from($request);
        $attachment = $action->execute($attachment, $updateData);
        // return response()->json(new AttachmentResource($attachment));
        return $this->responseSuccessData(new AttachmentResource($attachment));
    }
    
    /**
     * Delete an attachment.
     */
    public function destroy(Attachment $attachment, DeleteAttachmentAction $action): JsonResponse
    {
        $action->execute($attachment);
        // return response()->json(null, 204);
        return  $this->responseSuccessData([],msg:'Deleted Attachment Successfully.');
    }


    //
    public function reorder(Request $request, Item $item, ReorderAttachmentsAction $action): JsonResponse
    {
        // $data = $request->validate(['attachment_ids' => ['required', 'array']]);
        $validated = $request->validate([
            'attachment_ids' => ['required', 'array'],
            'attachment_ids.*' => ['integer', Rule::exists('attachments', 'id')],
        ]);

        $action->execute($item, $validated['attachment_ids']);
        // $action->execute($item, $data['attachment_ids']);
        return $this->responseSuccessData(['message' => 'Order updated.'],msg:'Order attachment update.');
        // return response()->json(['message' => 'Order updated.']);
    }

    public function setPrimary(Item $item, Attachment $attachment, SetPrimaryAttachmentAction $action): JsonResponse
    {
        $action->execute($item, $attachment);
        // return response()->json(['message' => 'Primary attachment set.']);
        return $this->responseSuccessData(['message' => 'Primary attachment set.'],msg:'Primary attachment set.');
    }

    public function bulkDelete(Request $request, BulkDeleteAttachmentsAction $action): JsonResponse
    {
        // $data = $request->validate(['attachment_ids' => ['required', 'array']]);
        // $action->execute($data['attachment_ids']);
        $validated = $request->validate([
            'attachment_ids' => ['required', 'array'],
            'attachment_ids.*' => ['integer', Rule::exists('attachments', 'id')],
        ]);
        $action->execute($validated['attachment_ids']);
        // return response()->json(null, 204);
        return  $this->responseSuccessData([],msg:'Deleted Attachments Successfully.');
    }
    
    public function library(Request $request): JsonResponse
    {
        $query = Attachment::query()->whereNotNull('thumbnail_path')->latest();
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->query('search') . '%')
            ->orWhere('original_name', 'like', '%' . $request->query('search') . '%');
        }
        $result=$query->paginate(18);
       return $this->responseSuccessData(AttachmentData::collect($result, PaginatedDataCollection::class));
        // return AttachmentResource::collection($query->paginate(18))->response();
        // return $this->responseData(['resource'=>AttachmentResource::collection($query->paginate(18))]);
        // return $this->handlePaginatedResponse(AttachmentResource::collection($query->paginate(18)), true,200);
        // return $this->handleResponse(['data'=>AttachmentResource::collection($query->paginate(18))], true,200);
        return $this->handleResponse(['data'=>AttachmentResource::collection($result)], true,200);
    }

    public function attachExisting(Request $request, Item $item, AttachExistingAttachmentsAction $action): JsonResponse
    {
        // $data = $request->validate(['attachment_ids' => ['required', 'array']]);
        // $action->execute($item, $data['attachment_ids']);
        $validated = $request->validate([
            'attachment_ids' => ['required', 'array'],
            'attachment_ids.*' => ['integer', Rule::exists('attachments', 'id')],
        ]);
        $action->execute($item, $validated['attachment_ids']);
        // return response()->json(['message' => 'Attachments linked.']);
      return  $this->responseSuccessData(['message' => 'Attachments linked.'],msg:'Linked Attachments Successfully.');
    }
    public function detachExisting(Request $request, Item $item, DetachExistingAttachmentsAction $action): JsonResponse
    {
        // $data = $request->validate(['attachment_ids' => ['required', 'array']]);
        // $action->execute($item, $data['attachment_ids']);
        $validated = $request->validate([
            'attachment_ids' => ['required', 'array'],
            'attachment_ids.*' => ['integer', Rule::exists('attachments', 'id')],
        ]);
        $action->execute($item, $validated['attachment_ids']);
        return $this->responseSuccessData(['message' => 'Attachments unlinked.'],msg:'Unlinked Attachments Successfully.');
    }
}