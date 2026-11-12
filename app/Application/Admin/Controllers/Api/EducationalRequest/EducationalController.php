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
use Domain\EducationalRequest\Models\RequestActivityLog;
use Domain\EducationalRequest\Repositories\EducationalRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
        $filters = $request->validate([
            'search' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'paymentStatus' => ['sometimes', 'nullable', Rule::in(['paid', 'unpaid', 'partially_paid'])],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);

        $relations = ['requester', 'project'];
        if (config('app.brand') === 'taqa360') {
            $relations[] = 'service';
        }
        if (config('app.brand') === 'huloolcom') {
            $relations[] = 'academicMajor';
        }

        $requests = EducationalRequest::with($relations)
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = trim($filters['search']);
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhereHas('requester', function ($requesterQuery) use ($search) {
                            $requesterQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(!empty($filters['paymentStatus']), fn ($query) => $query->where('payment_status', $filters['paymentStatus']))
            ->latest('id')
            ->paginate($filters['per_page'] ?? 10);

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
        return $this->responseData(data: [
            'educationalRequest' => new EducationalRequestResource(
                $educationalRequest->load($this->requestRelations(['requester', 'project', 'attachments', 'activityLog']))
            ),
        ]);
    }

    public function update(Request $request, EducationalRequest $educationalRequest)
    {
        try {
            $brandRules = config('app.brand') === 'huloolcom'
                ? [
                    'subject' => ['sometimes', 'nullable', 'string', 'max:100'],
                    'educationLevel' => ['sometimes', 'nullable', 'string', 'max:100'],
                    'education_level' => ['sometimes', 'nullable', 'string', 'max:100'],
                ]
                : [
                    'projectSiteType' => ['sometimes', 'nullable', 'in:residential,commercial,agricultural,industrial,medical,educational,other'],
                    'project_site_type' => ['sometimes', 'nullable', 'in:residential,commercial,agricultural,industrial,medical,educational,other'],
                    'solarSystemType' => ['sometimes', 'nullable', 'in:off-grid,on-grid,hybrid,not_sure'],
                    'solar_system_type' => ['sometimes', 'nullable', 'in:off-grid,on-grid,hybrid,not_sure'],
                ];

            $request->validate(array_merge([
                'serviceType' => ['sometimes', 'nullable', 'in:'.implode(',', EducationalRequestType::values())],
                'service_type' => ['sometimes', 'nullable', 'in:'.implode(',', EducationalRequestType::values())],
                'type' => ['sometimes', 'nullable', 'in:'.implode(',', EducationalRequestType::values())],
                'budget' => ['sometimes', 'nullable', 'numeric', 'min:0'],
                'expectedDeliveryDate' => ['sometimes', 'nullable', 'date'],
                'expected_delivery_date' => ['sometimes', 'nullable', 'date'],
            ], $brandRules));

            $mapped = [];
            if ($request->has('title'))                  $mapped['title'] = $request->input('title');
            if ($request->has('description'))            $mapped['description'] = $request->input('description');
            if ($request->has('budget'))                 $mapped['budget'] = $request->input('budget');
            $serviceType = $request->input('serviceType', $request->input('service_type', $request->input('type')));
            if ($serviceType !== null) {
                $mapped['type'] = $serviceType === '' ? EducationalRequestType::GENERAL->value : strtolower($serviceType);
            }
            if ($request->has('finalAgreement'))         $mapped['final_agreement'] = $request->input('finalAgreement');
            if ($request->has('final_agreement'))        $mapped['final_agreement'] = $request->input('final_agreement');
            if ($request->has('additionalNotes'))        $mapped['additional_notes'] = $request->input('additionalNotes');
            if ($request->has('additional_notes'))       $mapped['additional_notes'] = $request->input('additional_notes');

            if (config('app.brand') === 'huloolcom') {
                if ($request->has('educationLevel')) {
                    $mapped['education_level'] = $request->input('educationLevel');
                }
                if ($request->has('education_level')) {
                    $mapped['education_level'] = $request->input('education_level');
                }
            } else {
                if ($request->has('projectSiteType'))   $mapped['project_site_type'] = $request->input('projectSiteType');
                if ($request->has('project_site_type')) $mapped['project_site_type'] = $request->input('project_site_type');
                if ($request->has('solarSystemType'))   $mapped['solar_system_type'] = $request->input('solarSystemType');
                if ($request->has('solar_system_type')) $mapped['solar_system_type'] = $request->input('solar_system_type');
            }

            if (config('app.brand') === 'huloolcom' && $request->has('subject')) {
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

            DB::transaction(function () use ($request, $educationalRequest, $mapped) {
                $educationalRequest->loadMissing('project');
                if (config('app.brand') === 'huloolcom') {
                    $educationalRequest->loadMissing('academicMajor');
                }

                $project = $educationalRequest->project;
                $before = [
                    'title' => $educationalRequest->title,
                    'description' => $educationalRequest->description,
                    'budget' => $educationalRequest->budget,
                    'service_type' => $educationalRequest->type instanceof \BackedEnum ? $educationalRequest->type->value : $educationalRequest->type,
                    'final_agreement' => $educationalRequest->final_agreement,
                    'additional_notes' => $educationalRequest->additional_notes,
                    'deadline' => $project?->deadline?->toDateString(),
                ];
                if (config('app.brand') === 'huloolcom') {
                    $before['subject'] = $educationalRequest->academicMajor?->name;
                    $before['education_level'] = $educationalRequest->education_level;
                } else {
                    $before['project_site_type'] = $educationalRequest->project_site_type;
                    $before['solar_system_type'] = $educationalRequest->solar_system_type;
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
                        if ($project) {
                            $project->update(['deadline' => $deadline]);
                        } else {
                            $project = \Domain\Project\Models\Project::create([
                                'educational_request_id' => $educationalRequest->id,
                                'title' => $educationalRequest->title,
                                'description' => $educationalRequest->description,
                                'deadline' => $deadline,
                                'budget' => $educationalRequest->budget,
                                'created_by' => $adminId,
                            ]);
                        }
                    } elseif ($project) {
                        $project->update(['deadline' => null]);
                    }
                }

                if (array_key_exists('budget', $mapped) && $project) {
                    $project->update(['budget' => $mapped['budget']]);
                }

                if (config('app.brand') === 'huloolcom') {
                    $educationalRequest->load('academicMajor');
                }

                $after = [
                    'title' => $educationalRequest->title,
                    'description' => $educationalRequest->description,
                    'budget' => $educationalRequest->budget,
                    'service_type' => $educationalRequest->type instanceof \BackedEnum ? $educationalRequest->type->value : $educationalRequest->type,
                    'final_agreement' => $educationalRequest->final_agreement,
                    'additional_notes' => $educationalRequest->additional_notes,
                    'deadline' => $project?->fresh()?->deadline?->toDateString(),
                ];
                if (config('app.brand') === 'huloolcom') {
                    $after['subject'] = $educationalRequest->academicMajor?->name;
                    $after['education_level'] = $educationalRequest->education_level;
                } else {
                    $after['project_site_type'] = $educationalRequest->project_site_type;
                    $after['solar_system_type'] = $educationalRequest->solar_system_type;
                }
                $changes = [];
                foreach ($after as $field => $newValue) {
                    $oldValue = $before[$field];
                    if ((string) ($oldValue ?? '') !== (string) ($newValue ?? '')) {
                        $changes[$field] = [
                            'from' => $oldValue === null ? null : (string) $oldValue,
                            'to' => $newValue === null ? null : (string) $newValue,
                        ];
                    }
                }

                if ($changes) {
                    $actor = auth('admin-api')->user() ?? auth()->user();
                    RequestActivityLog::create([
                        'educational_request_id' => $educationalRequest->id,
                        'activity_type' => 'details',
                        'to_status' => 'details_updated',
                        'actor_name' => $actor?->name ?? $actor?->email ?? 'الإدارة',
                        'details' => json_encode($changes, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                    ]);
                }
            });

            return $this->responseData(
                data: ['educationalRequest' => new EducationalRequestResource(
                    $educationalRequest->fresh()->load($this->requestRelations(['requester', 'project', 'attachments', 'activityLog']))
                )],
                msg: 'تم تحديث الطلب بنجاح'
            );
        } catch (\Throwable $e) {
            Log::error('EducationalRequest update error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->responseError(msg: 'حدث خطأ أثناء تحديث الطلب: ' . $e->getMessage(), code: 500);
        }

    }

    private function requestRelations(array $relations): array
    {
        if (config('app.brand') === 'taqa360') {
            $relations[] = 'service';
        }

        if (config('app.brand') === 'huloolcom') {
            $relations[] = 'academicMajor';
        }

        return $relations;
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
            $newStatus = match ($type) {
                'payment' => $this->mapPaymentStatus($rawStatus),
                'fulfillment', 'request' => $this->mapRequestStatus($rawStatus),
            };

            if (!$newStatus) {
                $label = match ($type) {
                    'payment' => 'الدفع',
                    'fulfillment' => 'الإنجاز',
                    default => 'الطلب',
                };
                return $this->responseError(msg: "حالة {$label} غير صالحة: {$rawStatus}", code: 422);
            }

            $statusField = match ($type) {
                'payment' => 'payment_status',
                'fulfillment' => 'execution_status',
                default => 'status',
            };
            $fromStatus = $this->statusValue($educationalRequest->{$statusField});
            $toStatus = $this->statusValue($newStatus);
            $actor = auth('admin-api')->user() ?? auth()->user();

            DB::transaction(function () use ($educationalRequest, $type, $newStatus, $reason, $fromStatus, $toStatus, $actor) {
                $changes = [$type === 'payment' ? 'payment_status' : ($type === 'fulfillment' ? 'execution_status' : 'status') => $newStatus];
                if ($type === 'request') {
                    $changes += [
                        'reviewed_at' => now(),
                        'reviewed_by' => auth('admin-api')->id(),
                        'rejection_reason' => ($newStatus === EducationalRequestStatus::REJECTED) ? $reason : null,
                    ];
                }
                $educationalRequest->update($changes);

                if ($fromStatus !== $toStatus) {
                    RequestActivityLog::create([
                        'educational_request_id' => $educationalRequest->id,
                        'activity_type' => $type,
                        'from_status' => $fromStatus,
                        'to_status' => $toStatus,
                        'reason' => $reason,
                        'actor_name' => $actor?->name ?? $actor?->email ?? 'الإدارة',
                    ]);
                }
            });

            return $this->responseData(
                data: ['educationalRequest' => new EducationalRequestResource($educationalRequest->fresh()->load('requester', 'project', 'attachments', 'activityLog'))],
                msg: 'تم تغيير الحالة بنجاح'
            );
        } catch (\Throwable $e) {
            Log::error('EducationalRequest changeStatus error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->responseError(msg: 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage(), code: 500);
        }
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof \BackedEnum ? (string) $status->value : (string) $status;
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
