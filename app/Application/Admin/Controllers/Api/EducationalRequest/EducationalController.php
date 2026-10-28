<?php

namespace App\Application\Admin\Controllers\Api\EducationalRequest;

use App\Application\Educational\Enums\EducationalRequestStatus;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Application\Educational\Requests\StoreEducationalRequest;
use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Domain\EducationalRequest\Actions\ReviewEducationalRequestAction;
use App\Domain\EducationalRequest\Resources\EducationalListRequestResource;
use App\Domain\EducationalRequest\Resources\EducationalRequestResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\PaymentStatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EducationalController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        private EducationalRequestRepository $repository,
        private CreateEducationalRequestAction $createAction,
        private ReviewEducationalRequestAction $reviewAction
    ) {}


    public function fetch(Request $request)
    {
        $requests = EducationalRequest::with(['requester', 'academicMajor', 'project'])
            ->latest('id')
            ->paginate($request->get('per_page', 15));

        return $this->responseData(data:['educationalRequests' => EducationalListRequestResource::collection($requests)]);
    }

    public function store(StoreEducationalRequest $request, CreateEducationalRequestAction $createAction)
    {
        try {
            $educationalRequest = $createAction->handle(
                array_merge($request->validated(), ['user_id' => 1])
            );

            return $this->responseData(data:['educationalRequest' => new EducationalRequestResource($educationalRequest)], msg:"EducationalRequest created successfully", code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to create EducationalRequest", code: 500);
        }
    }

    public function show(EducationalRequest $educationalRequest)
    {
        return $this->responseData(data:['educationalRequest' => new EducationalRequestResource($educationalRequest->load('requester','project','attachments','academicMajor'))]);
    }

    public function update(Request $request, EducationalRequest $educationalRequest)
    {
        try {
            $mapped = [];
            if ($request->has('title'))                  $mapped['title'] = $request->input('title');
            if ($request->has('description'))            $mapped['description'] = $request->input('description');
            if ($request->has('budget'))                 $mapped['budget'] = $request->input('budget');
            if ($request->has('pricingType'))            $mapped['type'] = strtolower($request->input('pricingType'));
            if ($request->has('pricing_type'))           $mapped['type'] = strtolower($request->input('pricing_type'));
            if ($request->has('type'))                   $mapped['type'] = strtolower($request->input('type'));
            if ($request->has('finalAgreement'))         $mapped['final_agreement'] = $request->input('finalAgreement');
            if ($request->has('final_agreement'))        $mapped['final_agreement'] = $request->input('final_agreement');
            if ($request->has('educationLevel'))         $mapped['education_level'] = $request->input('educationLevel');
            if ($request->has('education_level'))        $mapped['education_level'] = $request->input('education_level');
            if ($request->has('additionalNotes'))        $mapped['additional_notes'] = $request->input('additionalNotes');
            if ($request->has('additional_notes'))       $mapped['additional_notes'] = $request->input('additional_notes');

            if ($request->has('subject')) {
                $subjectName = $request->input('subject');
                if ($subjectName) {
                    $major = \Domain\AppBase\Models\Edu\AcademicMajor::where('name->ar', $subjectName)
                        ->orWhere('name->en', $subjectName)
                        ->orWhere('name', 'like', '%'.$subjectName.'%')
                        ->first();
                    if (!$major) {
                        $major = \Domain\AppBase\Models\Edu\AcademicMajor::create(['name' => $subjectName]);
                    }
                    $mapped['academic_major_id'] = $major->id;
                }
            }

            if (!empty($mapped)) {
                $educationalRequest->update($mapped);
            }

            if ($request->has('expectedDeliveryDate') || $request->has('expected_delivery_date')) {
                $deadline = $request->input('expectedDeliveryDate') ?? $request->input('expected_delivery_date');
                if ($deadline) {
                    $adminId = auth('admin-api')->id()
                        ?? auth()->id()
                        ?? \Domain\Admin\Models\Admin::query()->min('id');
                    if ($educationalRequest->project) {
                        $educationalRequest->project->update(['deadline' => $deadline]);
                    } else {
                        \Domain\Project\Models\Project::create([
                            'educational_request_id' => $educationalRequest->id,
                            'title' => $educationalRequest->title,
                            'description' => $educationalRequest->description,
                            'deadline' => $deadline,
                            'budget' => $educationalRequest->budget,
                            'created_by' => $adminId,
                        ]);
                    }
                }
            }

            return $this->responseData(
                data: ['educationalRequest' => new EducationalRequestResource($educationalRequest->fresh()->load('requester', 'project', 'attachments', 'academicMajor'))],
                msg: 'تم تحديث الطلب بنجاح'
            );
        } catch (\Throwable $e) {
            Log::error('EducationalRequest update error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->responseError(msg: 'حدث خطأ أثناء تحديث الطلب: ' . $e->getMessage(), code: 500);
        }
    }

    public function changeStatus(Request $request, EducationalRequest $educationalRequest)
    {
        try {
            $request->validate([
                'status' => 'required|string',
                'reason' => 'nullable|string|max:500',
                'type'   => 'nullable|string|in:request,payment,fulfillment',
            ]);

            $type        = $request->input('type', 'request');
            $rawStatus   = (string) $request->input('status');
            $reason      = $request->input('reason');

            switch ($type) {
                case 'payment':
                    $enumStatus = $this->mapPaymentStatus($rawStatus);
                    if (!$enumStatus) {
                        return $this->responseError(msg: "حالة الدفع غير صالحة: {$rawStatus}", code: 422);
                    }
                    $educationalRequest->update(['payment_status' => $enumStatus]);
                    break;

                case 'fulfillment':
                    $enumStatus = $this->mapRequestStatus($rawStatus);
                    if (!$enumStatus) {
                        return $this->responseError(msg: "حالة الإنجاز غير صالحة: {$rawStatus}", code: 422);
                    }
                    $educationalRequest->update(['execution_status' => $enumStatus]);
                    break;

                default:
                    $enumStatus = $this->mapRequestStatus($rawStatus);
                    if (!$enumStatus) {
                        return $this->responseError(msg: "حالة الطلب غير صالحة: {$rawStatus}", code: 422);
                    }
                    $educationalRequest->update([
                        'status'           => $enumStatus,
                        'reviewed_at'      => now(),
                        'rejection_reason' => ($enumStatus === EducationalRequestStatus::REJECTED) ? $reason : null,
                    ]);
                    break;
            }

            return $this->responseData(
                data: ['educationalRequest' => new EducationalRequestResource($educationalRequest->fresh()->load('requester', 'project', 'attachments'))],
                msg: 'تم تغيير الحالة بنجاح'
            );
        } catch (\Throwable $e) {
            Log::error('EducationalRequest changeStatus error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->responseError(msg: 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage(), code: 500);
        }
    }

    private function mapRequestStatus(string $status): ?EducationalRequestStatus
    {
        $statusClean = strtolower(str_replace('_', '-', trim($status)));
        if ($statusClean === 'accepted') {
            $statusClean = 'approved';
        }
        return EducationalRequestStatus::tryFrom($statusClean);
    }

    private function mapPaymentStatus(string $status): ?PaymentStatusEnum
    {
        $statusClean = strtolower(trim($status));
        if ($statusClean === 'partially_paid' || $statusClean === 'partially-paid' || $statusClean === 'partial') {
            return PaymentStatusEnum::PARTIALLY_PAID;
        }
        return PaymentStatusEnum::tryFrom($statusClean);
    }
}
